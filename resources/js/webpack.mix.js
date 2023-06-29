module.exports = {
    scanForCssSelectors: [],
    webpackPlugins: [],
    safelist: [],
    install: [
    ],
    copy: [
    ],
    mix: {
        css: [
            { input: 'app/modules/cortex/settings/resources/sass/module.scss', output: 'public/css/settings.css' },
        ],
        js: [
            { input: 'app/modules/cortex/settings/resources/js/module.js', output: 'public/js/settings.js' },
        ]
    }
};
