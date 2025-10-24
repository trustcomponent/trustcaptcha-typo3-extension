define([
    'jquery',
    'TYPO3/CMS/Form/Backend/FormEditor/Helper'
], function ($, Helper) {
    'use strict';

    var _formEditorApp = null;

    function getFormEditorApp() {
        return _formEditorApp;
    }
    function getPublisherSubscriber() {
        return getFormEditorApp().getPublisherSubscriber();
    }
    function assert(test, message, messageCode) {
        return getFormEditorApp().assert(test, message, messageCode);
    }
    function _helperSetup() {
        assert('function' === $.type(Helper.bootstrap),
            'The view model helper does not implement the method "bootstrap"',
            1491643380
        );
        Helper.bootstrap(getFormEditorApp());
    }

    function _subscribeEvents() {
        getPublisherSubscriber().subscribe('view/stage/abstract/render/template/perform', function (topic, args) {
            try {
                var formElement = args[0];
                var template    = args[1];
                if (formElement && formElement.get && formElement.get('type') === 'TrustCaptcha') {
                    getFormEditorApp().getViewModel().getStage().renderSimpleTemplateWithValidators(formElement, template);
                }
            } catch (e) {
                /* no-op */
            }
        });
    }

    function bootstrap(formEditorApp) {
        try {
            if (formEditorApp && formEditorApp.__tcTrustCaptchaBootstrapped) {
                return;
            }
            if (formEditorApp) {
                Object.defineProperty(formEditorApp, '__tcTrustCaptchaBootstrapped', {
                    value: true,
                    enumerable: false,
                    configurable: true,
                    writable: false
                });
            }
        } catch (e) { }
        _formEditorApp = formEditorApp;
        _helperSetup();
        _subscribeEvents();
    }

    return {
        bootstrap: bootstrap
    };
});
