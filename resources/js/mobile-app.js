// resources/js/mobile-app.js

import { Capacitor } from '@capacitor/core';
import { Preferences } from '@capacitor/preferences';
import { Network } from '@capacitor/network';
import { Camera } from '@capacitor/camera';
import { Geolocation } from '@capacitor/geolocation';
import { PushNotifications } from '@capacitor/push-notifications';
import axios from 'axios';

const isNative = Capacitor.isNativePlatform();

class MobileApp {
    constructor() {
        this.isOnline = true;
        this.authToken = null;
        this.user = null;
    }

    // Initialize the mobile application
    async initialize() {
        if (isNative) {
            await this.setupNetworkListeners();
            await this.setupNotifications();
            await this.restoreSession();
        }
    }

    // Network monitoring
    async setupNetworkListeners() {
        const status = await Network.getStatus();
        this.isOnline = status.connected;

        await Network.addListener('networkStatusChange', status => {
            this.isOnline = status.connected;
            this.handleConnectivityChange(status);
        });
    }

    // Push notifications setup
    async setupNotifications() {
        try {
            const permission = await PushNotifications.requestPermissions();
            if (permission.receive === 'granted') {
                await PushNotifications.register();
                this.setupNotificationHandlers();
            }
        } catch (error) {
            console.error('Notification setup failed:', error);
        }
    }

    // Notification event handlers
    setupNotificationHandlers() {
        PushNotifications.addListener('registration', token => {
            // Send token to your server
            this.updatePushToken(token.value);
        });

        PushNotifications.addListener('pushNotificationReceived', notification => {
            // Handle received notification
            console.log('Notification received:', notification);
        });

        PushNotifications.addListener('pushNotificationActionPerformed', notification => {
            // Handle notification action
            this.handleNotificationAction(notification);
        });
    }

    // Authentication
    async login(email, password) {
        try {
            const response = await axios.post('/api/mobile/login', {
                email,
                password
            });

            if (response.data.token) {
                await this.setAuthToken(response.data.token);
                await this.setUserData(response.data.user);
                return true;
            }
            return false;
        } catch (error) {
            console.error('Login failed:', error);
            throw error;
        }
    }

    // Session management
    async setAuthToken(token) {
        this.authToken = token;
        await Preferences.set({
            key: 'auth_token',
            value: token
        });
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    }

    async setUserData(user) {
        this.user = user;
        await Preferences.set({
            key: 'user_data',
            value: JSON.stringify(user)
        });
    }

    async restoreSession() {
        const token = await Preferences.get({ key: 'auth_token' });
        const userData = await Preferences.get({ key: 'user_data' });

        if (token?.value) {
            this.authToken = token.value;
            axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
        }

        if (userData?.value) {
            this.user = JSON.parse(userData.value);
        }
    }

    // Retribusi collection features
    async collectRetribution(data) {
        try {
            if (!this.isOnline) {
                await this.saveOfflineCollection(data);
                return { status: 'offline', message: 'Data saved offline' };
            }

            const response = await axios.post('/api/retribusi-pembayarans', data);
            return response.data;
        } catch (error) {
            console.error('Collection failed:', error);
            await this.saveOfflineCollection(data);
            throw error;
        }
    }

    // Offline data handling
    async saveOfflineCollection(data) {
        const offlineData = await Preferences.get({ key: 'offline_collections' });
        const collections = offlineData?.value ? JSON.parse(offlineData.value) : [];

        collections.push({
            ...data,
            timestamp: new Date().toISOString(),
            location: await this.getCurrentLocation()
        });

        await Preferences.set({
            key: 'offline_collections',
            value: JSON.stringify(collections)
        });
    }

    async syncOfflineData() {
        if (!this.isOnline) return;

        const offlineData = await Preferences.get({ key: 'offline_collections' });
        if (!offlineData?.value) return;

        const collections = JSON.parse(offlineData.value);
        const syncedData = [];
        const failedData = [];

        for (const collection of collections) {
            try {
                await axios.post('/api/retribusi-pembayarans', collection);
                syncedData.push(collection);
            } catch (error) {
                failedData.push(collection);
            }
        }

        await Preferences.set({
            key: 'offline_collections',
            value: JSON.stringify(failedData)
        });

        return { syncedCount: syncedData.length, failedCount: failedData.length };
    }

    // Location services
    async getCurrentLocation() {
        try {
            const position = await Geolocation.getCurrentPosition();
            return {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            };
        } catch (error) {
            console.error('Location error:', error);
            return null;
        }
    }

    // Camera functionality
    async capturePhoto() {
        try {
            const image = await Camera.getPhoto({
                quality: 90,
                allowEditing: false,
                resultType: 'base64',
                source: 'CAMERA'
            });
            return image;
        } catch (error) {
            console.error('Camera error:', error);
            throw error;
        }
    }

    // Data fetching methods
    async fetchPedagang() {
        try {
            const response = await axios.get('/api/pedagangs');
            return response.data;
        } catch (error) {
            console.error('Failed to fetch pedagang:', error);
            throw error;
        }
    }

    async fetchPasar() {
        try {
            const response = await axios.get('/api/pasars');
            return response.data;
        } catch (error) {
            console.error('Failed to fetch pasar:', error);
            throw error;
        }
    }

    async fetchDailyStats() {
        try {
            const response = await axios.get('/api/mobile/daily-stats');
            return response.data;
        } catch (error) {
            console.error('Failed to fetch daily stats:', error);
            throw error;
        }
    }

    // Utility methods
    handleConnectivityChange(status) {
        if (status.connected) {
            this.syncOfflineData();
        }
    }

    async handleNotificationAction(notification) {
        // Handle different notification types
        switch (notification.notification.data.type) {
            case 'collection_reminder':
                // Handle collection reminder
                break;
            case 'sync_required':
                await this.syncOfflineData();
                break;
            // Add more notification types as needed
        }
    }

    async updatePushToken(token) {
        try {
            await axios.post('/api/mobile/update-push-token', { token });
        } catch (error) {
            console.error('Failed to update push token:', error);
         }
    }
}

const mobileApp = new MobileApp();
mobileApp.initialize();
