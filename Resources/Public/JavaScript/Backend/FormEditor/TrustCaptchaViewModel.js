import $ from 'jquery';
import * as Helper from '@typo3/form/backend/form-editor/helper.js';

let _formEditorApp = null;

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
    assert('function' === $.type(Helper.bootstrap), 'The view model helper does not implement the method "bootstrap"', 1491643380);
    Helper.bootstrap(getFormEditorApp());
}

function _subscribeEvents() {
    getPublisherSubscriber().subscribe('view/stage/abstract/render/template/perform', (topic, args) => {
        try {
            const formElement = args[0];
            const template    = args[1];
            if (formElement && formElement.get && formElement.get('type') === 'TrustCaptcha') {
                getFormEditorApp().getViewModel().getStage().renderSimpleTemplateWithValidators(formElement, template);
            }
        } catch (e) { }
    });
}

export function bootstrap(formEditorApp) {
    if (formEditorApp && formEditorApp.__tcTrustCaptchaBootstrapped) {
        return;
    }
    if (formEditorApp) {
        Object.defineProperty(formEditorApp, '__tcTrustCaptchaBootstrapped', {
            value: true,
            enumerable: false,
            configurable: true,
            writable: false,
        });
    }

    _formEditorApp = formEditorApp;
    _helperSetup();
    _subscribeEvents();
}
