// Vuetify 3 setup for Vue 3
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import '@mdi/font/css/materialdesignicons.css';

export default createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                    primary: '#2A9D8F',
                    secondary: '#264653',
                    surface: '#FFFFFF',
                    background: '#FFFFFF',
                    error: '#E76F51',
                    'on-surface': '#333333',
                },
            },
        },
    },
    defaults: {
        VTextField: {
            color: 'primary',
            baseColor: 'secondary',
        },
    },
});

