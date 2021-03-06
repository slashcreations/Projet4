// TODO: TINY-1645 This helper is duplicated from Alloy/tests, its used by the mobile theme, also the alloy-docs project has this duplicated too

import { Assertions, Pipeline, Step } from '@ephox/agar';
import { Attachment, Gui, Debugging } from '@ephox/alloy';
import { Merger, Id } from '@ephox/katamari';
import { DomEvent, Element, Html, Insert, Remove } from '@ephox/sugar';

import TestStore from './TestStore';
import { document } from '@ephox/dom-globals';

const setup = function (createComponent, f, success, failure) {
  const store = TestStore();

  const gui = Gui.create();

  const doc = Element.fromDom(document);
  const body = Element.fromDom(document.body);

  Attachment.attachSystem(body, gui);
  Debugging.registerInspector(Id.generate('test-case'), gui);

  const component = createComponent(store, doc, body);
  gui.add(component);

  Pipeline.async({}, f(doc, body, gui, component, store), function () {
    Attachment.detachSystem(gui);
    success();
  }, function (e) {
    // tslint:disable-next-line:no-console
    console.error(e);
    failure(e);
  });
};

const mSetupKeyLogger = function (body) {
  return Step.stateful(function (_, next, die) {
    const onKeydown = DomEvent.bind(body, 'keydown', function (event) {
      newState.log.push('keydown.to.body: ' + event.raw().which);
    });

    const log = [ ];
    const newState = {
      log,
      onKeydown
    };
    next(newState);
  });
};

const mTeardownKeyLogger = function (body, expected) {
  return Step.stateful(function (state: any, next, die) {
    Assertions.assertEq('Checking key log outside context (on teardown)', expected, state.log);
    state.onKeydown.unbind();
    next({});
  });
};

const mAddStyles = function (doc, styles) {
  return Step.stateful(function (value, next, die) {
    const style = Element.fromTag('style');
    const head = Element.fromDom(doc.dom().head);
    Insert.append(head, style);
    Html.set(style, styles.join('\n'));

    next(Merger.deepMerge(value, {
      style
    }));
  });
};

const mRemoveStyles = Step.stateful(function (value: any, next, die) {
  Remove.remove(value.style);
  next(value);
});

export default {
  setup,
  mSetupKeyLogger,
  mTeardownKeyLogger,

  mAddStyles,
  mRemoveStyles
};