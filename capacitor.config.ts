import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.retribusipasar.app',
  appName: 'Retribusi Pasar',
  webDir: 'public',
  server: {
    androidScheme: 'https',
    // For development, you can use your local server
    url: 'http://192.168.8.101:8000', // Replace with your local IP
    cleartext: true
  },
  android: {
    buildOptions: {
      keystorePath: 'release-key.keystore',
      keystoreAlias: 'key0',
      keystorePassword: '12345678',
      keystoreAliasPassword: '12345678',
    }
  }
};

export default config;
