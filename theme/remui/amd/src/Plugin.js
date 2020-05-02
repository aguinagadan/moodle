define(["exports", "jquery"], function (exports, _jquery) {
  "use strict";

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.pluginFactory = exports.getDefaults = exports.getPlugin = exports.getPluginAPI = exports.Plugin = undefined;

  var _jquery2 = _interopRequireDefault(_jquery);

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule ? obj : {
      default: obj
    };
  }

  let plugins = {};
  let apis = {};

  class Plugin {
    constructor($el, options = {}) {
      this.name = this.getName();
      this.$el = $el;
      this.options = options;
      this.isRendered = false;
    }

    getName() {
      return 'plugin';
    }

    render() {
      if (_jquery2.default.fn[this.name]) {
        this.$el[this.name](this.options);
      } else {
        return false;
      }
    }

    initialize() {
      if (this.isRendered) {
        return false;
      }

      this.render();
      this.isRendered = true;
    }

    static getDefaults() {
      return {};
    }

    static register(name, obj) {
      if (typeof obj === 'undefined') {
        return;
      }

      plugins[name] = obj;

      if (typeof obj.api !== 'undefined') {
        Plugin.registerApi(name, obj);
      }
    }

    static registerApi(name, obj) {
      let api = obj.api();

      if (typeof api === 'string') {
        let api = obj.api().split('|');
        let event = api[0] + `.plugin.${name}`;
        let func = api[1] || 'render';

        let callback = function (e) {
          let $el = (0, _jquery2.default)(this);
          let plugin = $el.data('pluginInstance');

          if (!plugin) {
            plugin = new obj($el, _jquery2.default.extend(true, {}, getDefaults(name), $el.data()));
            plugin.initialize();
            $el.data('pluginInstance', plugin);
          }

          plugin[func](e);
        };

        apis[name] = function (selector, context) {
          if (context) {
            (0, _jquery2.default)(context).off(event);
            (0, _jquery2.default)(context).on(event, selector, callback);
          } else {
            (0, _jquery2.default)(selector).on(event, callback);
          }
        };
      } else if (typeof api === 'function') {
        apis[name] = api;
      }
    }

  }

  exports.default = Plugin;

  function getPluginAPI(name) {
    if (typeof name === 'undefined') {
      return apis;
    } else {
      return apis[name];
    }
  }

  function getPlugin(name) {
    if (typeof plugins[name] !== 'undefined') {
      return plugins[name];
    } else {
      console.warn('Plugin:' + name + ' has no warpped class.');
      return false;
    }
  }

  function getDefaults(name) {
    let PluginClass = getPlugin(name);

    if (PluginClass) {
      return PluginClass.getDefaults();
    } else {
      return {};
    }
  }

  function pluginFactory(name, $el, options = {}) {
    let PluginClass = getPlugin(name);

    if (PluginClass && typeof PluginClass.api === 'undefined') {
      return new PluginClass($el, _jquery2.default.extend(true, {}, getDefaults(name), options));
    } else if (_jquery2.default.fn[name]) {
      let plugin = new Plugin($el, options);

      plugin.getName = function () {
        return name;
      };

      plugin.name = name;
      return plugin;
    } else if (typeof PluginClass.api !== 'undefined') {
      // console.log('Plugin:' + name + ' use api render.');
      return false;
    } else {
      console.warn('Plugin:' + name + ' script is not loaded.');
      return false;
    }
  }

  exports.Plugin = Plugin;
  exports.getPluginAPI = getPluginAPI;
  exports.getPlugin = getPlugin;
  exports.getDefaults = getDefaults;
  exports.pluginFactory = pluginFactory;

  return Plugin;
});