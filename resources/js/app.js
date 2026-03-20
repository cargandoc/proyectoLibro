import './bootstrap';
import '../css/app.css';

require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

createInertiaApp({
    title: (title) => `${title} - Mi Biblioteca`,
    resolve: (name) => require(`./Pages/${name}.vue`).default,
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } }) 
            .mount(el);
    },
});

InertiaProgress.init({ color: '#3B82F6' });
