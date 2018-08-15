/******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	function webpackJsonpCallback(data) {
/******/ 		var chunkIds = data[0];
/******/ 		var moreModules = data[1];
/******/ 		var executeModules = data[2];
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [];
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(data);
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 		// add entry modules from loaded chunk to deferred list
/******/ 		deferredModules.push.apply(deferredModules, executeModules || []);
/******/
/******/ 		// run deferred modules when all chunks ready
/******/ 		return checkDeferredModules();
/******/ 	};
/******/ 	function checkDeferredModules() {
/******/ 		var result;
/******/ 		for(var i = 0; i < deferredModules.length; i++) {
/******/ 			var deferredModule = deferredModules[i];
/******/ 			var fulfilled = true;
/******/ 			for(var j = 1; j < deferredModule.length; j++) {
/******/ 				var depId = deferredModule[j];
/******/ 				if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 			}
/******/ 			if(fulfilled) {
/******/ 				deferredModules.splice(i--, 1);
/******/ 				result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 			}
/******/ 		}
/******/ 		return result;
/******/ 	}
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// object to store loaded and loading chunks
/******/ 	// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 	// Promise = chunk loading, 0 = chunk loaded
/******/ 	var installedChunks = {
/******/ 		"index": 0
/******/ 	};
/******/
/******/ 	// script path function
/******/ 	function jsonpScriptSrc(chunkId) {
/******/ 		return __webpack_require__.p + "" + ({}[chunkId]||chunkId) + ".js"
/******/ 	}
/******/
/******/ 	var deferredModules = [];
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	var jsonpArray = window["webpackJsonp"] = window["webpackJsonp"] || [];
/******/ 	var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
/******/ 	jsonpArray.push = webpackJsonpCallback;
/******/ 	jsonpArray = jsonpArray.slice();
/******/ 	for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
/******/ 	var parentJsonpFunction = oldJsonpFunction;
/******/
/******/
/******/ 	// add entry module to deferred list
/******/ 	deferredModules.push(["./src/js/index.js","vendor"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/css-loader/index.js!./node_modules/sass-loader/lib/loader.js!./src/style/index.scss":
/*!*************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/sass-loader/lib/loader.js!./src/style/index.scss ***!
  \*************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "body {\n  padding-top: 50px; }\n\n.no-margin {\n  margin: 0px; }\n\n.no-padding {\n  padding: 0px; }\n\n.show {\n  position: fixed;\n  top: 5%;\n  right: 1%; }\n\n.hidden {\n  display: none !important; }\n\n.section-url {\n  padding-top: 60px; }\n\n.section-question {\n  padding-top: 30px;\n  background: #e8f5ff; }\n\n.questions {\n  padding-top: 30px; }\n  .questions .option {\n    height: 40px; }\n\n.download-btn {\n  position: fixed;\n  top: 8px;\n  right: 10px;\n  z-index: 1030; }\n\n.questions-nav.sticky-top {\n  top: 56px; }\n\n.badges {\n  padding: 50px 0px; }\n", ""]);

// exports


/***/ }),

/***/ "./src/img/logo.png":
/*!**************************!*\
  !*** ./src/img/logo.png ***!
  \**************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__.p + "logo.png";

/***/ }),

/***/ "./src/js/components/App.js":
/*!**********************************!*\
  !*** ./src/js/components/App.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

var _GetQuiz = __webpack_require__(/*! ./GetQuiz */ "./src/js/components/GetQuiz.js");

var _GetQuiz2 = _interopRequireDefault(_GetQuiz);

var _ShowQuiz = __webpack_require__(/*! ./ShowQuiz */ "./src/js/components/ShowQuiz.js");

var _ShowQuiz2 = _interopRequireDefault(_ShowQuiz);

var _logo = __webpack_require__(/*! ../../img/logo.png */ "./src/img/logo.png");

var _logo2 = _interopRequireDefault(_logo);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var App = function (_React$Component) {
    _inherits(App, _React$Component);

    function App(props) {
        _classCallCheck(this, App);

        var _this = _possibleConstructorReturn(this, (App.__proto__ || Object.getPrototypeOf(App)).call(this, props));

        _this.state = {
            url: '',
            titleBanner: ''
        };
        return _this;
    }

    _createClass(App, [{
        key: 'urlAPI',
        value: function urlAPI(url) {
            this.setState({
                url: 'https://assets.breatheco.de/apis/quiz/' + url
            });
        }
    }, {
        key: 'render',
        value: function render() {
            return _react2.default.createElement(
                'div',
                null,
                _react2.default.createElement(
                    'nav',
                    { className: 'navbar fixed-top navbar-light bg-light' },
                    _react2.default.createElement(
                        'a',
                        { className: 'navbar-brand', href: '#' },
                        _react2.default.createElement('img', { src: 'https://assets.breatheco.de/apis/img/images.php?blob&random&cat=icon&tags=breathecode,64', width: '30', height: '30', className: 'd-inline-block align-top mr-2', alt: '' }),
                        'Quiz Maker - BreatheCode'
                    )
                ),
                this.state.url ? _react2.default.createElement(_ShowQuiz2.default, { data: this.state.url }) : _react2.default.createElement(_GetQuiz2.default, { onSelect: this.urlAPI.bind(this) })
            );
        }
    }]);

    return App;
}(_react2.default.Component);

exports.default = App;

/***/ }),

/***/ "./src/js/components/GetQuiz.js":
/*!**************************************!*\
  !*** ./src/js/components/GetQuiz.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var GetQuiz = function (_React$Component) {
    _inherits(GetQuiz, _React$Component);

    function GetQuiz() {
        _classCallCheck(this, GetQuiz);

        var _this = _possibleConstructorReturn(this, (GetQuiz.__proto__ || Object.getPrototypeOf(GetQuiz)).call(this));

        _this.state = {
            slugQuiz: '',
            allQuiz: []
        };
        return _this;
    }

    _createClass(GetQuiz, [{
        key: 'handleGetQuiz',
        value: function handleGetQuiz() {
            var _this2 = this;

            fetch('https://assets.breatheco.de/apis/quiz/all').then(function (response) {
                return response.json();
            }).then(function (data) {
                _this2.setState({
                    allQuiz: data
                });
            }).catch(function (error) {
                console.log('error', error);
            });
        }
    }, {
        key: 'componentDidMount',
        value: function componentDidMount() {
            this.handleGetQuiz();
        }
    }, {
        key: 'handleSubmit',
        value: function handleSubmit(event) {
            event.preventDefault();
            console.log(this.state.slugQuiz);
            this.props.onSelect(this.state.slugQuiz);
        }
    }, {
        key: 'handleChange',
        value: function handleChange(event) {
            console.log(event.target.value);
            this.setState({
                slugQuiz: event.target.value
            });
        }
    }, {
        key: 'render',
        value: function render() {
            var _this3 = this;

            var styleOption = {
                width: '100%',
                height: '100%'
            };
            var slugQuiz = Object.entries(this.state.allQuiz);
            var optionSelect = slugQuiz.map(function (value, key) {
                return value[1].info.slug ? _react2.default.createElement(
                    'option',
                    { key: key, value: value[1].info.slug, onChange: function onChange(event) {
                            return _this3.handleChange(event);
                        } },
                    value[1].info.slug
                ) : '';
            });
            return _react2.default.createElement(
                'div',
                { className: 'alert alert-primary' },
                _react2.default.createElement(
                    'h4',
                    { className: 'alert-heading' },
                    'Load Quiz from URL'
                ),
                _react2.default.createElement(
                    'form',
                    { onSubmit: function onSubmit(event) {
                            return _this3.handleSubmit(event);
                        } },
                    _react2.default.createElement(
                        'div',
                        { className: 'form-row' },
                        _react2.default.createElement(
                            'div',
                            { className: 'form-group col-md-8' },
                            _react2.default.createElement(
                                'select',
                                { style: styleOption, onChange: function onChange(event) {
                                        return _this3.handleChange(event);
                                    } },
                                _react2.default.createElement(
                                    'option',
                                    { defaultValue: true },
                                    'All Quiz'
                                ),
                                optionSelect
                            )
                        ),
                        _react2.default.createElement(
                            'div',
                            { className: 'form-group col' },
                            _react2.default.createElement(
                                'button',
                                { type: 'submit', className: 'btn btn-light form-control' },
                                'Load Quiz'
                            )
                        )
                    )
                )
            );
        }
    }]);

    return GetQuiz;
}(_react2.default.Component);

exports.default = GetQuiz;

/***/ }),

/***/ "./src/js/components/Info.js":
/*!***********************************!*\
  !*** ./src/js/components/Info.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

var _NewBadges = __webpack_require__(/*! ./NewBadges */ "./src/js/components/NewBadges.js");

var _NewBadges2 = _interopRequireDefault(_NewBadges);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Info = function (_React$Component) {
    _inherits(Info, _React$Component);

    function Info(props) {
        _classCallCheck(this, Info);

        var _this = _possibleConstructorReturn(this, (Info.__proto__ || Object.getPrototypeOf(Info)).call(this, props));

        _this.state = {
            nameInfo: '',
            mainInfo: '',
            resultInfo: '',
            badges: [],
            slug: ''
        };
        return _this;
    }

    _createClass(Info, [{
        key: 'componentWillReceiveProps',
        value: function componentWillReceiveProps(props) {
            this.setState({
                nameInfo: props.data.name,
                mainInfo: props.data.main,
                resultInfo: props.data.results,
                slug: props.data.slug,
                badges: props.data.badges
            });
        }
    }, {
        key: 'handleChangeName',
        value: function handleChangeName(event) {
            this.setState({
                nameInfo: event.target.value
            });

            this.props.handleJsonInfo({
                name: event.target.value,
                main: this.state.mainInfo,
                results: this.state.resultInfo,
                badges: this.state.badges,
                slug: this.state.slug
            }, { typeRequest: 'name' });
        }
    }, {
        key: 'handleChangeMain',
        value: function handleChangeMain(event) {
            this.setState({
                mainInfo: event.target.value
            });

            this.props.handleJsonInfo({
                name: this.state.nameInfo,
                main: event.target.value,
                results: this.state.resultInfo,
                badges: this.state.badges,
                slug: this.state.slug
            }, { typeRequest: 'nameMain' });
        }
    }, {
        key: 'handleChangeResult',
        value: function handleChangeResult(event) {
            this.setState({
                resultInfo: event.target.value
            });

            this.props.handleJsonInfo({
                name: this.state.nameInfo,
                main: this.state.mainInfo,
                results: event.target.value,
                badges: this.state.badges,
                slug: this.state.slug
            }, { typeRequest: 'nameResult' });
        }
    }, {
        key: 'handleChangeSlug',
        value: function handleChangeSlug(event, value, key) {
            this.setState({
                slug: event.target.value
            });

            this.props.handleJsonInfo({
                name: this.state.nameInfo,
                main: this.state.mainInfo,
                results: this.state.resultInfo,
                badges: this.state.badges,
                slug: event.target.value
            }, { typeRequest: 'nameSlug' });
        }
    }, {
        key: 'handleChangeNameBadges',
        value: function handleChangeNameBadges(event, value, key) {
            console.log(event, value, key);
            var resultBadges = this.state.badges.map(function (data, id) {
                if (key == id) {
                    return { slug: event.target.value, points: data.points };
                } else {
                    return { slug: data.slug, points: data.points };
                }
            });
            this.setState({
                badges: resultBadges
            });

            this.props.handleJsonInfo({
                name: this.state.nameInfo,
                main: this.state.mainInfo,
                results: this.state.resultInfo,
                badges: resultBadges,
                slug: this.state.slug
            }, { typeRequest: 'nameBadges' });
        }
    }, {
        key: 'handleChangePointBadges',
        value: function handleChangePointBadges(event, value, key) {
            console.log(event, value, key);
            var resultBadges = this.state.badges.map(function (data, id) {
                if (key == id) {
                    return { slug: data.slug, points: event.target.value };
                } else {
                    return { slug: data.slug, points: data.points };
                }
            });
            this.setState({
                badges: resultBadges
            });

            this.props.handleJsonInfo({
                name: this.state.nameInfo,
                main: this.state.mainInfo,
                results: this.state.resultInfo,
                badges: resultBadges,
                slug: this.state.slug
            }, { typeRequest: 'pointBadges' });
        }
    }, {
        key: 'handleNewBadges',
        value: function handleNewBadges() {
            var newBadges = {
                slug: '',
                points: ''
            };
            this.setState({
                badges: [].concat(_toConsumableArray(this.state.badges), [newBadges])
            });
        }
    }, {
        key: 'handleDeleteBadges',
        value: function handleDeleteBadges(data, i) {
            var filteredBadges = this.state.badges.filter(function (el) {
                return el != data;
            });
            this.setState({
                badges: filteredBadges
            });
        }
    }, {
        key: 'render',
        value: function render() {
            var _this2 = this;

            var resultBadges = null;
            resultBadges = this.state.badges.map(function (value, key) {
                return _react2.default.createElement(
                    'div',
                    { className: 'form-row', key: key },
                    _react2.default.createElement(
                        'div',
                        { className: 'form-group col-md-4' },
                        _react2.default.createElement('input', {
                            type: 'text',
                            className: 'form-control',
                            value: value.slug,
                            onChange: function onChange(event) {
                                return _this2.handleChangeNameBadges(event, value, key);
                            },
                            placeholder: 'Slug'
                        })
                    ),
                    _react2.default.createElement(
                        'div',
                        { className: 'form-group col-md-4' },
                        _react2.default.createElement('input', {
                            type: 'number',
                            className: 'form-control',
                            value: value.points,
                            onChange: function onChange(event) {
                                return _this2.handleChangePointBadges(event, value, key);
                            },
                            placeholder: 'Points'
                        })
                    ),
                    _react2.default.createElement(
                        'button',
                        {
                            type: 'button',
                            onClick: function onClick() {
                                return _this2.handleDeleteBadges(value, key);
                            },
                            className: 'btn text-danger float-right' },
                        'delete'
                    )
                )

                /*<div className="form-group" key={key}>
                    <label>Badges</label>
                    <input
                        type="text"
                        className="form-control"
                        value={value.slug}
                        onChange={(event)=>this.handleChangeNameBadges(event, value, key)}
                    />
                    <label>Points</label>
                    <input
                        type="number"
                        className="form-control"
                        value={value.points}
                        onChange={(event)=>this.handleChangePointBadges(event, value, key)}
                    />
                </div>*/
                ;
            });
            return _react2.default.createElement(
                'div',
                null,
                _react2.default.createElement(
                    'div',
                    { className: 'section-question p-4' },
                    _react2.default.createElement(
                        'div',
                        { className: 'form-group' },
                        _react2.default.createElement(
                            'label',
                            null,
                            'Quiz title'
                        ),
                        _react2.default.createElement('input', {
                            type: 'text',
                            className: 'form-control',
                            value: this.state.nameInfo,
                            onChange: this.handleChangeName.bind(this)
                        })
                    ),
                    _react2.default.createElement(
                        'div',
                        { className: 'form-group' },
                        _react2.default.createElement(
                            'label',
                            null,
                            'Description shown to the student before starting the quiz'
                        ),
                        _react2.default.createElement('input', {
                            type: 'text',
                            className: 'form-control',
                            value: this.state.mainInfo,
                            onChange: this.handleChangeMain.bind(this)
                        })
                    ),
                    _react2.default.createElement(
                        'div',
                        { className: 'form-group' },
                        _react2.default.createElement(
                            'label',
                            null,
                            'Last message once the quiz is over'
                        ),
                        _react2.default.createElement('input', {
                            type: 'text',
                            className: 'form-control',
                            value: this.state.resultInfo,
                            onChange: this.handleChangeResult.bind(this)
                        })
                    ),
                    _react2.default.createElement(
                        'div',
                        { className: 'form-group' },
                        _react2.default.createElement(
                            'label',
                            null,
                            'Slug'
                        ),
                        _react2.default.createElement('input', {
                            type: 'text',
                            className: 'form-control',
                            value: this.state.slug,
                            onChange: this.handleChangeSlug.bind(this)
                        })
                    )
                ),
                _react2.default.createElement(
                    'div',
                    { className: 'row' },
                    _react2.default.createElement(
                        'div',
                        { className: 'col-12' },
                        _react2.default.createElement(
                            'nav',
                            { className: 'questions-nav navbar sticky-top navbar-dark bg-dark' },
                            _react2.default.createElement(
                                'a',
                                { className: 'navbar-brand', href: '#' },
                                'Badges'
                            ),
                            _react2.default.createElement(
                                'div',
                                { className: 'ml-auto' },
                                _react2.default.createElement(_NewBadges2.default, { onClick: this.handleNewBadges.bind(this) })
                            )
                        )
                    ),
                    _react2.default.createElement(
                        'div',
                        { className: 'col-12 col-sm-10 col-md-8 col-xl-6 mx-auto badges' },
                        resultBadges
                    )
                )
            );
        }
    }]);

    return Info;
}(_react2.default.Component);

exports.default = Info;

/***/ }),

/***/ "./src/js/components/NewBadges.js":
/*!****************************************!*\
  !*** ./src/js/components/NewBadges.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var NewQuestion = function (_React$Component) {
  _inherits(NewQuestion, _React$Component);

  function NewQuestion(props) {
    _classCallCheck(this, NewQuestion);

    var _this = _possibleConstructorReturn(this, (NewQuestion.__proto__ || Object.getPrototypeOf(NewQuestion)).call(this, props));

    _this.state = {};
    return _this;
  }

  _createClass(NewQuestion, [{
    key: "newBadges",
    value: function newBadges() {
      this.props.onClick();
    }
  }, {
    key: "render",
    value: function render() {
      return _react2.default.createElement(
        "div",
        { className: "" },
        _react2.default.createElement(
          "button",
          {
            type: "button",
            className: "btn btn-primary",
            onClick: this.newBadges.bind(this) },
          _react2.default.createElement("i", { className: "fas fa-plus-circle" }),
          " Add Badges"
        )
      );
    }
  }]);

  return NewQuestion;
}(_react2.default.Component);

exports.default = NewQuestion;

/***/ }),

/***/ "./src/js/components/NewQuestion.js":
/*!******************************************!*\
  !*** ./src/js/components/NewQuestion.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var NewQuestion = function (_React$Component) {
  _inherits(NewQuestion, _React$Component);

  function NewQuestion(props) {
    _classCallCheck(this, NewQuestion);

    var _this = _possibleConstructorReturn(this, (NewQuestion.__proto__ || Object.getPrototypeOf(NewQuestion)).call(this, props));

    _this.state = {};
    return _this;
  }

  _createClass(NewQuestion, [{
    key: "newQuestion",
    value: function newQuestion() {
      this.props.onClick();
    }
  }, {
    key: "render",
    value: function render() {
      return _react2.default.createElement(
        "div",
        { className: "" },
        _react2.default.createElement(
          "button",
          {
            type: "button",
            className: "btn btn-primary",
            onClick: this.newQuestion.bind(this) },
          _react2.default.createElement("i", { className: "fas fa-plus-circle" }),
          " Add Question"
        )
      );
    }
  }]);

  return NewQuestion;
}(_react2.default.Component);

exports.default = NewQuestion;

/***/ }),

/***/ "./src/js/components/Questions.js":
/*!****************************************!*\
  !*** ./src/js/components/Questions.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Questions = function (_React$Component) {
	_inherits(Questions, _React$Component);

	function Questions(props) {
		_classCallCheck(this, Questions);

		var _this = _possibleConstructorReturn(this, (Questions.__proto__ || Object.getPrototypeOf(Questions)).call(this, props));

		_this.state = {
			valueQuestion: [],
			valueOption: [],
			isChecked: false,
			showMessage: false,
			editing: false
		};

		_this.handleChangeOptions = _this.handleChangeOptions.bind(_this);
		return _this;
	}

	_createClass(Questions, [{
		key: 'componentWillReceiveProps',
		value: function componentWillReceiveProps(nextProps) {
			this.setState({
				valueQuestion: nextProps.data.q,
				valueOption: nextProps.answer
			});
		}
	}, {
		key: 'componentWillMount',
		value: function componentWillMount() {
			this.setState({
				valueQuestion: this.props.data.q,
				valueOption: this.props.answer
			});
		}
	}, {
		key: 'handleChangeQuestion',
		value: function handleChangeQuestion(event) {
			this.setState({
				valueQuestion: event.target.value
			});
			this.props.handleJsonQuestion(event.target.value, { typeRequest: 'question' });
		}
	}, {
		key: 'handleChangeOptions',
		value: function handleChangeOptions(event, key) {
			var newOptions = this.state.valueOption.map(function (opt, i) {
				if (key == i) {
					return { option: event.target.value, correct: opt.correct };
				} else {
					return opt;
				}
			});
			this.setState({
				valueOption: newOptions
			});
			this.props.handleJsonQuestion(newOptions, { typeRequest: 'option' }, key);
		}
	}, {
		key: 'handleChangeOptionsTrueFalse',
		value: function handleChangeOptionsTrueFalse(event, key) {
			this.setState({
				isChecked: !this.state.isChecked
			});
			if (this.state.isChecked) {
				var newOptions = this.state.valueOption.map(function (opt, i) {
					if (key == i) {
						return { option: opt.option, correct: true };
					} else {
						return opt;
					}
				});
				this.setState({
					valueOption: newOptions
				});
				this.props.handleJsonQuestion(newOptions, { typeRequest: 'checkbox' });
			} else {
				var _newOptions = this.state.valueOption.map(function (opt, i) {
					if (key == i) {
						return { option: opt.option, correct: false };
					} else {
						return opt;
					}
				});
				this.setState({
					valueOption: _newOptions
				});
				this.props.handleJsonQuestion(_newOptions, { typeRequest: 'checkbox' });
			}
		}
	}, {
		key: 'handleDeleteQuestion',
		value: function handleDeleteQuestion() {
			var _this2 = this;

			this.setState({
				showMessage: true
			});
			setTimeout(function () {
				_this2.setState({
					showMessage: false
				});
			}, 2000);
			this.props.onSelect();
		}
	}, {
		key: 'handleDeleteOpcion',
		value: function handleDeleteOpcion(data, key) {
			this.props.onDeleteOption(data, key);
		}
	}, {
		key: 'handleNewOption',
		value: function handleNewOption() {
			this.props.onNewOption();
		}
	}, {
		key: 'handleUpQuestion',
		value: function handleUpQuestion() {
			this.props.onUpQuestion();
		}
	}, {
		key: 'handleDownQuestion',
		value: function handleDownQuestion() {
			this.props.onDownQuestion();
		}
	}, {
		key: 'render',
		value: function render() {
			var _this3 = this;

			var options = null;
			options = this.state.valueOption.map(function (value, key) {
				return _react2.default.createElement(
					'div',
					{ className: 'row option', key: key },
					_react2.default.createElement(
						'div',
						{ className: 'col-8' },
						_react2.default.createElement('input', {
							className: 'form-control',
							type: 'text',
							value: value.option,
							onChange: function onChange(event) {
								return _this3.handleChangeOptions(event, key);
							},
							placeholder: 'Type your option value'
						})
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-2' },
						_react2.default.createElement(
							'label',
							{ className: 'float-right' },
							'Correct?'
						),
						_react2.default.createElement('input', {
							type: 'checkbox',
							checked: value.correct,
							value: value.correct,
							onChange: function onChange(event) {
								return _this3.handleChangeOptionsTrueFalse(event, key);
							}
						})
					),
					_react2.default.createElement(
						'div',
						{ className: 'col-2' },
						_react2.default.createElement(
							'button',
							{ className: 'btn text-danger', onClick: function onClick() {
									return _this3.handleDeleteOpcion(value, key);
								} },
							_react2.default.createElement('i', { className: 'fas fa-trash-alt text-danger' })
						)
					)
				);
			});

			return _react2.default.createElement(
				'div',
				{ className: 'questions' },
				_react2.default.createElement(
					'div',
					{ className: "message-delete " + (this.state.showMessage ? "show " : "hidden") },
					_react2.default.createElement(
						'div',
						{ className: 'alert alert-danger', role: 'alert' },
						'Question Deleted'
					)
				),
				_react2.default.createElement(
					'button',
					{
						type: 'button',
						className: 'btn text-danger float-right',
						onClick: this.handleDeleteQuestion.bind(this) },
					'delete question'
				),
				_react2.default.createElement(
					'button',
					{ className: "btn text-primary " + (this.props.firstQuestion ? "hidden" : ""), onClick: function onClick() {
							return _this3.handleUpQuestion();
						} },
					_react2.default.createElement('i', { 'class': 'fas fa-arrow-circle-up' })
				),
				_react2.default.createElement(
					'button',
					{ className: "btn text-primary " + (this.props.lastQuestion ? "hidden" : ""), onClick: function onClick() {
							return _this3.handleDownQuestion();
						} },
					_react2.default.createElement('i', { 'class': 'fas fa-arrow-circle-down' })
				),
				_react2.default.createElement(
					'div',
					{ className: 'form-group' },
					_react2.default.createElement('input', {
						className: 'form-control',
						type: 'text',
						value: this.state.valueQuestion,
						onChange: function onChange(event) {
							return _this3.handleChangeQuestion(event);
						},
						placeholder: 'Type your question title'
					})
				),
				options,
				_react2.default.createElement(
					'div',
					{ className: 'row' },
					_react2.default.createElement(
						'div',
						{ className: 'col-12' },
						_react2.default.createElement(
							'button',
							{ className: 'btn text-primary', onClick: function onClick() {
									return _this3.handleNewOption();
								} },
							_react2.default.createElement('i', { className: 'fas fa-plus-circle' }),
							' new option'
						)
					)
				)
			);
		}
	}]);

	return Questions;
}(_react2.default.Component);

exports.default = Questions;

/***/ }),

/***/ "./src/js/components/ShowQuiz.js":
/*!***************************************!*\
  !*** ./src/js/components/ShowQuiz.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

var _Info = __webpack_require__(/*! ./Info */ "./src/js/components/Info.js");

var _Info2 = _interopRequireDefault(_Info);

var _Questions = __webpack_require__(/*! ./Questions */ "./src/js/components/Questions.js");

var _Questions2 = _interopRequireDefault(_Questions);

var _NewQuestion = __webpack_require__(/*! ./NewQuestion */ "./src/js/components/NewQuestion.js");

var _NewQuestion2 = _interopRequireDefault(_NewQuestion);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ShowQuiz = function (_React$Component) {
	_inherits(ShowQuiz, _React$Component);

	function ShowQuiz(props) {
		_classCallCheck(this, ShowQuiz);

		var _this = _possibleConstructorReturn(this, (ShowQuiz.__proto__ || Object.getPrototypeOf(ShowQuiz)).call(this, props));

		_this.state = {
			apiBadges: [],
			apiInfo: [],
			apiQuestions: [],
			json: []
		};
		return _this;
	}

	_createClass(ShowQuiz, [{
		key: 'componentWillMount',
		value: function componentWillMount() {
			this.getApi();
		}

		//Consulta API

	}, {
		key: 'getApi',
		value: function getApi() {
			var _this2 = this;

			fetch(this.props.data).then(function (response) {
				return response.json();
			}).then(function (data) {
				_this2.setState({
					apiInfo: data.info,
					apiQuestions: data.questions,
					apiBadges: data.info.badges
				});
			}).catch(function (error) {
				console.log('error', error);
			});
		}

		//Agregar pregunta

	}, {
		key: 'handleNewQuestion',
		value: function handleNewQuestion() {
			var newQuestion = {
				q: '',
				a: [{
					option: '',
					correct: false
				}, {
					option: '',
					correct: false
				}, {
					option: '',
					correct: false
				}]
			};
			this.setState({
				apiQuestions: [].concat(_toConsumableArray(this.state.apiQuestions), [newQuestion])
			});
			setTimeout(function () {
				return window.scrollTo(0, document.body.scrollHeight);
			}, 500);
		}

		//Eliminar pregunta

	}, {
		key: 'handleDeleteQuestion',
		value: function handleDeleteQuestion(data, i) {
			var filteredQuestions = this.state.apiQuestions.filter(function (el) {
				return el != data;
			});
			this.setState({
				apiQuestions: filteredQuestions
			});
		}

		//Eliminar option de pregunta

	}, {
		key: 'handleDeleteOption',
		value: function handleDeleteOption(data, key, idOption) {
			var filteredQuestions = this.state.apiQuestions.map(function (value, i) {
				if (key == i) {
					value.a.splice(idOption, 1);
					return { q: value.q, a: value.a };
				} else {
					return { q: value.q, a: value.a };
				}
			});

			this.setState({
				apiQuestions: filteredQuestions
			});
		}

		//Agregar nueva opcion a pregunta

	}, {
		key: 'handleNewOption',
		value: function handleNewOption(key) {
			var resultNewOption = this.state.apiQuestions.map(function (value, i) {
				if (key == i) {
					value.a.push({ option: '', correct: false });
					return { q: value.q, a: value.a };
				} else {
					return { q: value.q, a: value.a };
				}
			});

			this.setState({
				apiQuestions: resultNewOption
			});
		}
	}, {
		key: 'handleUpQuestion',
		value: function handleUpQuestion(key) {
			var arr = this.state.apiQuestions;
			var old_index = key;
			var new_index = key - 1;
			//this.handleActionUpQuestion(this.state.apiQuestions, old_index, new_index);
			if (new_index >= arr.length) {
				var k = new_index - arr.length + 1;
				while (k--) {
					arr.push(undefined);
				}
			}
			arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

			this.setState({
				apiQuestions: arr
			});
		}
	}, {
		key: 'handleDownQuestion',
		value: function handleDownQuestion(key) {
			var arr = this.state.apiQuestions;
			var old_index = key;
			var new_index = key + 1;
			if (new_index >= arr.length) {
				var k = new_index - arr.length + 1;
				while (k--) {
					arr.push(undefined);
				}
			}
			arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);

			this.setState({
				apiQuestions: arr
			});
		}

		//Datos que recibo para modificar el json del API

	}, {
		key: 'getValueFromQuestion',
		value: function getValueFromQuestion(data, type, idQuestion, idOption) {
			if (type.typeRequest == 'question') {
				var resultQuestions = this.state.apiQuestions.map(function (value, i) {
					if (idQuestion == i) {
						return { q: data, a: value.a };
					} else {
						return { q: value.q, a: value.a };
					}
				});
				this.setState({
					apiQuestions: resultQuestions
				});
			} else if (type.typeRequest == 'option') {
				var resultOption = this.state.apiQuestions.map(function (value, idQ) {
					if (idQuestion == idQ) {
						return { q: value.q, a: data };
					} else {
						return { q: value.q, a: value.a };
					}
				});
				this.setState({
					apiQuestions: resultOption
				});
			} else if (type.typeRequest == 'checkbox') {
				var resultCheckbox = this.state.apiQuestions.map(function (value, idQ) {
					if (idQuestion == idQ) {
						return { q: value.q, a: data };
					} else {
						return { q: value.q, a: value.a };
					}
				});
				this.setState({
					apiQuestions: resultCheckbox
				});
			}
		}
	}, {
		key: 'getValueFromInfo',
		value: function getValueFromInfo(data, type) {
			if (type.typeRequest == 'nameBadges' || type.typeRequest == 'pointBadges' || type.typeRequest == 'nameSlug' || type.typeRequest == 'nameResult' || type.typeRequest == 'nameMain' || type.typeRequest == 'name') {
				this.setState({
					apiInfo: data
				});
			}
		}
	}, {
		key: 'download',
		value: function download(filename) {
			var buildJson = {
				info: {
					name: this.state.apiInfo.name,
					main: this.state.apiInfo.main,
					results: this.state.apiInfo.results,
					badges: this.state.apiInfo.badges,
					slug: this.state.apiInfo.slug
				},
				questions: this.state.apiQuestions
			};

			var text = JSON.stringify(buildJson);
			var element = document.createElement('a');
			element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
			element.setAttribute('download', filename);
			element.style.display = 'none';
			document.body.appendChild(element);
			element.click();
			document.body.removeChild(element);
		}
	}, {
		key: 'render',
		value: function render() {
			var _this3 = this;

			var results = this.state.apiQuestions.map(function (value, key) {
				var lastQuestion = _this3.state.apiQuestions.length - 1;
				if (key == 0) {
					return _react2.default.createElement(_Questions2.default, {
						key: key,
						data: value,
						answer: value.a,
						handleJsonQuestion: function handleJsonQuestion(data, type, idOption) {
							return _this3.getValueFromQuestion(data, type, key, idOption);
						},
						onSelect: function onSelect() {
							return _this3.handleDeleteQuestion(value, key);
						},
						onDeleteOption: function onDeleteOption(value, idOption) {
							return _this3.handleDeleteOption(value, key, idOption);
						},
						onNewOption: function onNewOption() {
							return _this3.handleNewOption(key);
						},
						onUpQuestion: function onUpQuestion() {
							return _this3.handleUpQuestion(key);
						},
						onDownQuestion: function onDownQuestion() {
							return _this3.handleDownQuestion(key);
						},
						firstQuestion: true });
				} else if (key < lastQuestion) {
					return _react2.default.createElement(_Questions2.default, {
						key: key,
						data: value,
						answer: value.a,
						handleJsonQuestion: function handleJsonQuestion(data, type, idOption) {
							return _this3.getValueFromQuestion(data, type, key, idOption);
						},
						onSelect: function onSelect() {
							return _this3.handleDeleteQuestion(value, key);
						},
						onDeleteOption: function onDeleteOption(value, idOption) {
							return _this3.handleDeleteOption(value, key, idOption);
						},
						onNewOption: function onNewOption() {
							return _this3.handleNewOption(key);
						},
						onUpQuestion: function onUpQuestion() {
							return _this3.handleUpQuestion(key);
						},
						onDownQuestion: function onDownQuestion() {
							return _this3.handleDownQuestion(key);
						} });
				} else {
					return _react2.default.createElement(_Questions2.default, {
						key: key,
						data: value,
						answer: value.a,
						handleJsonQuestion: function handleJsonQuestion(data, type, idOption) {
							return _this3.getValueFromQuestion(data, type, key, idOption);
						},
						onSelect: function onSelect() {
							return _this3.handleDeleteQuestion(value, key);
						},
						onDeleteOption: function onDeleteOption(value, idOption) {
							return _this3.handleDeleteOption(value, key, idOption);
						},
						onNewOption: function onNewOption() {
							return _this3.handleNewOption(key);
						},
						onUpQuestion: function onUpQuestion() {
							return _this3.handleUpQuestion(key);
						},
						onDownQuestion: function onDownQuestion() {
							return _this3.handleDownQuestion(key);
						},
						lastQuestion: true });
				}
			});
			return _react2.default.createElement(
				'div',
				{ className: 'container-fluid p-0' },
				_react2.default.createElement(
					'button',
					{ className: 'btn btn-primary download-btn', onClick: function onClick() {
							return _this3.download("quiz.json");
						} },
					_react2.default.createElement('i', { className: 'fas fa-download' }),
					' download progress'
				),
				_react2.default.createElement(
					'nav',
					{ className: 'navbar navbar-dark bg-dark' },
					_react2.default.createElement(
						'a',
						{ className: 'navbar-brand', href: '#' },
						'General Quiz Information'
					)
				),
				_react2.default.createElement(_Info2.default, { data: this.state.apiInfo, handleJsonInfo: function handleJsonInfo(data, type) {
						return _this3.getValueFromInfo(data, type);
					} }),
				_react2.default.createElement(
					'nav',
					{ className: 'questions-nav navbar sticky-top navbar-dark bg-dark' },
					_react2.default.createElement(
						'a',
						{ className: 'navbar-brand', href: '#' },
						'Questions'
					),
					_react2.default.createElement(
						'div',
						{ className: 'ml-auto' },
						_react2.default.createElement(_NewQuestion2.default, { onClick: this.handleNewQuestion.bind(this) })
					)
				),
				_react2.default.createElement(
					'div',
					{ className: 'row' },
					_react2.default.createElement(
						'div',
						{ className: 'col-12 col-sm-10 col-md-8 col-xl-6 mx-auto' },
						results
					)
				)
			);
		}
	}]);

	return ShowQuiz;
}(_react2.default.Component);

exports.default = ShowQuiz;

/***/ }),

/***/ "./src/js/icons.js":
/*!*************************!*\
  !*** ./src/js/icons.js ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _fontawesome = __webpack_require__(/*! @fortawesome/fontawesome */ "./node_modules/@fortawesome/fontawesome/index.es.js");

var _fontawesome2 = _interopRequireDefault(_fontawesome);

var _faCheck = __webpack_require__(/*! @fortawesome/fontawesome-free-solid/faCheck */ "./node_modules/@fortawesome/fontawesome-free-solid/faCheck.js");

var _faCheck2 = _interopRequireDefault(_faCheck);

var _faTrashAlt = __webpack_require__(/*! @fortawesome/fontawesome-free-solid/faTrashAlt */ "./node_modules/@fortawesome/fontawesome-free-solid/faTrashAlt.js");

var _faTrashAlt2 = _interopRequireDefault(_faTrashAlt);

var _faPlusCircle = __webpack_require__(/*! @fortawesome/fontawesome-free-solid/faPlusCircle */ "./node_modules/@fortawesome/fontawesome-free-solid/faPlusCircle.js");

var _faPlusCircle2 = _interopRequireDefault(_faPlusCircle);

var _faDownload = __webpack_require__(/*! @fortawesome/fontawesome-free-solid/faDownload */ "./node_modules/@fortawesome/fontawesome-free-solid/faDownload.js");

var _faDownload2 = _interopRequireDefault(_faDownload);

var _faArrowCircleUp = __webpack_require__(/*! @fortawesome/fontawesome-free-solid/faArrowCircleUp */ "./node_modules/@fortawesome/fontawesome-free-solid/faArrowCircleUp.js");

var _faArrowCircleUp2 = _interopRequireDefault(_faArrowCircleUp);

var _faArrowCircleDown = __webpack_require__(/*! @fortawesome/fontawesome-free-solid/faArrowCircleDown */ "./node_modules/@fortawesome/fontawesome-free-solid/faArrowCircleDown.js");

var _faArrowCircleDown2 = _interopRequireDefault(_faArrowCircleDown);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_fontawesome2.default.config = {
  autoReplaceSvg: 'nest'
}; /* global FontAwesomeConfig */

_fontawesome2.default.library.add(_faCheck2.default, _faTrashAlt2.default, _faPlusCircle2.default, _faDownload2.default, _faArrowCircleUp2.default, _faArrowCircleDown2.default);

/***/ }),

/***/ "./src/js/index.js":
/*!*************************!*\
  !*** ./src/js/index.js ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _react = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var _react2 = _interopRequireDefault(_react);

var _reactDom = __webpack_require__(/*! react-dom */ "./node_modules/react-dom/index.js");

var _reactDom2 = _interopRequireDefault(_reactDom);

var _App = __webpack_require__(/*! ./components/App */ "./src/js/components/App.js");

var _App2 = _interopRequireDefault(_App);

__webpack_require__(/*! ../style/index.scss */ "./src/style/index.scss");

__webpack_require__(/*! ./icons.js */ "./src/js/icons.js");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

_reactDom2.default.render(_react2.default.createElement(_App2.default, null), document.getElementById('app'));

/***/ }),

/***/ "./src/style/index.scss":
/*!******************************!*\
  !*** ./src/style/index.scss ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../node_modules/css-loader!../../node_modules/sass-loader/lib/loader.js!./index.scss */ "./node_modules/css-loader/index.js!./node_modules/sass-loader/lib/loader.js!./src/style/index.scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ })

/******/ });
//# sourceMappingURL=index.js.map