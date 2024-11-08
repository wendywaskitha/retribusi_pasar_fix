import { Capacitor } from '@capacitor/core';
import { Network } from '@capacitor/network';
import { Camera } from '@capacitor/camera';
import { Preferences } from '@capacitor/preferences';

// Check if running in Capacitor
const isNative = Capacitor.isNativePlatform();

// Network status monitoring
Network.addListener('networkStatusChange', status => {
    console.log('Network status changed', status);
});

// Example function to check network status
async function checkNetwork() {
    const status = await Network.getStatus();
    return status.connected;
}

// Example function to take photo
async function takePhoto() {
    const image = await Camera.getPhoto({
        quality: 90,
        allowEditing: false,
        resultType: 'uri'
    });
    return image;
}

// Export functions for use in your app
export {
    isNative,
    checkNetwork,
    takePhoto
};
