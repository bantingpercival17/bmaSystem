! function (t, e) {
    "object" == typeof exports && "object" == typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define([], e) : "object" == typeof exports ? exports.Viewer = e() : t.Viewer = e()
}(this, (function () {
    return function (t) {
        var e = {};

        function n(i) {
            if (e[i]) return e[i].exports;
            var o = e[i] = {
                i: i,
                l: !1,
                exports: {}
            };
            return t[i].call(o.exports, o, o.exports, n), o.l = !0, o.exports
        }
        return n.m = t, n.c = e, n.d = function (t, e, i) {
            n.o(t, e) || Object.defineProperty(t, e, {
                enumerable: !0,
                get: i
            })
        }, n.r = function (t) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {
                value: "Module"
            }), Object.defineProperty(t, "__esModule", {
                value: !0
            })
        }, n.t = function (t, e) {
            if (1 & e && (t = n(t)), 8 & e) return t;
            if (4 & e && "object" == typeof t && t && t.__esModule) return t;
            var i = Object.create(null);
            if (n.r(i), Object.defineProperty(i, "default", {
                    enumerable: !0,
                    value: t
                }), 2 & e && "string" != typeof t)
                for (var o in t) n.d(i, o, function (e) {
                    return t[e]
                }.bind(null, o));
            return i
        }, n.n = function (t) {
            var e = t && t.__esModule ? function () {
                return t.default
            } : function () {
                return t
            };
            return n.d(e, "a", e), e
        }, n.o = function (t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        }, n.p = "", n(n.s = 11)
    }([function (t, e) {
        var n = t.exports = "undefined" != typeof window && window.Math == Math ? window : "undefined" != typeof self && self.Math == Math ? self : Function("return this")();
        "number" == typeof __g && (__g = n)
    }, function (t, e) {
        var n = t.exports = {
            version: "2.6.12"
        };
        "number" == typeof __e && (__e = n)
    }, function (t, e, n) {
        var i = n(14),
            o = n(19);
        t.exports = n(4) ? function (t, e, n) {
            return i.f(t, e, o(1, n))
        } : function (t, e, n) {
            return t[e] = n, t
        }
    }, function (t, e) {
        t.exports = function (t) {
            return "object" == typeof t ? null !== t : "function" == typeof t
        }
    }, function (t, e, n) {
        t.exports = !n(5)((function () {
            return 7 != Object.defineProperty({}, "a", {
                get: function () {
                    return 7
                }
            }).a
        }))
    }, function (t, e) {
        t.exports = function (t) {
            try {
                return !!t()
            } catch (t) {
                return !0
            }
        }
    }, function (t, e) {
        var n = 0,
            i = Math.random();
        t.exports = function (t) {
            return "Symbol(".concat(void 0 === t ? "" : t, ")_", (++n + i).toString(36))
        }
    }, function (t, e, n) {
        var i = n(1),
            o = n(0),
            r = o["__core-js_shared__"] || (o["__core-js_shared__"] = {});
        (t.exports = function (t, e) {
            return r[t] || (r[t] = void 0 !== e ? e : {})
        })("versions", []).push({
            version: i.version,
            mode: n(23) ? "pure" : "global",
            copyright: "© 2020 Denis Pushkarev (zloirock.ru)"
        })
    }, function (t, e) {
        var n = Math.ceil,
            i = Math.floor;
        t.exports = function (t) {
            return isNaN(t = +t) ? 0 : (t > 0 ? i : n)(t)
        }
    }, function (t, e) {
        t.exports = function (t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        }
    }, function (t, e) {
        function n(t, e) {
            for (var n = 0; n < e.length; n++) {
                var i = e[n];
                i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
            }
        }
        t.exports = function (t, e, i) {
            return e && n(t.prototype, e), i && n(t, i), t
        }
    }, function (t, e, n) {
        "use strict";
        n.r(e), n.d(e, "default", (function () {
            return u
        })), n(12);
        var i = n(9),
            o = n.n(i),
            r = n(10),
            s = n.n(r),
            u = function () {
                function t(e, n) {
                    o()(this, t), this.canvas = e, this.image = n, this.originalImg = n, this.ctx = this.canvas.getContext("2d"), this.getFitSize = function () {
                        var t = 0,
                            e = 0,
                            n = this.imageW,
                            i = this.imageH,
                            o = this.cavW,
                            r = this.cavH;
                        return n < o && i < r ? (t = .5 * o - .5 * n, e = .5 * r - .5 * i) : i / n > r / o ? (n = n * r / i, i = r, t = .5 * o - .5 * n) : (i = i * o / n, n = o, e = .5 * r - .5 * i), {
                            x: t,
                            y: e,
                            w: n,
                            h: i
                        }
                    }, this.isNum = function (t) {
                        return /^(-?\d+)(\.\d+)?$/.test(t)
                    }, this.init()
                }
                return s()(t, [{
                    key: "init",
                    value: function () {
                        this.initData(), this.initListener()
                    }
                }, {
                    key: "initData",
                    value: function () {
                        var t = this.image,
                            e = t.width,
                            n = t.height,
                            i = this.canvas,
                            o = i.width,
                            r = i.height;
                        this.imgScale = 1, this.angle = 0, this.isVRevert = 1, this.isHRevert = 1, this.isMove = !1, this.imageW = e, this.imageH = n, this.cavW = o, this.cavH = r;
                        var s = this.getFitSize();
                        this.imgX = s.x, this.imgY = s.y, this.image = this.originalImg
                    }
                }, {
                    key: "initListener",
                    value: function () {
                        this.canvas.addEventListener("mousedown", this, !1), this.canvas.addEventListener("mouseup", this, !1), this.canvas.addEventListener("mouseout", this, !1), this.canvas.addEventListener("mousewheel", this, !1)
                    }
                }, {
                    key: "handleEvent",
                    value: function (t) {
                        switch (t.type) {
                            case "mousemove":
                                this.mousemove(t);
                                break;
                            case "mousewheel":
                                this.mousewheel(t);
                                break;
                            case "mouseup":
                                this.mouseup(t);
                                break;
                            case "mousedown":
                                this.mousedown(t);
                                break;
                            case "mouseout":
                                this.mouseout(t)
                        }
                    }
                }, {
                    key: "mouseout",
                    value: function () {
                        this.isMove = !1, this.canvas.style.cursor = "default ", this.canvas.removeEventListener("mousemove", this, !1)
                    }
                }, {
                    key: "mousedown",
                    value: function (t) {
                        this.mouseDownPos = this.windowToCanvas(t.clientX, t.clientY), this.isMove = !0, this.canvas.style.cursor = "move", this.canvas.addEventListener("mousemove", this, !1)
                    }
                }, {
                    key: "mouseup",
                    value: function () {
                        this.isMove = !1, this.canvas.style.cursor = "default ", this.canvas.removeEventListener("mousemove", this, !1)
                    }
                }, {
                    key: "mousemove",
                    value: function (t) {
                        if (this.isMove) {
                            var e = this.mouseDownPos;
                            this.canvas.style.cursor = "move";
                            var n = this.windowToCanvas(t.clientX, t.clientY),
                                i = n.x - e.x,
                                o = n.y - e.y;
                            this.mouseDownPos = n, this.imgX += i, this.imgY += o, this.draw()
                        }
                    }
                }, {
                    key: "mousewheel",
                    value: function (t) {
                        var e = this.windowToCanvas(t.clientX, t.clientY);
                        (t.wheelDelta ? t.wheelDelta : -40 * t.deltaY) > 0 ? (this.imgScale *= 2, this.imgX = 2 * this.imgX - e.x, this.imgY = 2 * this.imgY - e.y) : (this.imgScale /= 2, this.imgX = .5 * this.imgX + .5 * e.x, this.imgY = .5 * this.imgY + .5 * e.y), this.draw()
                    }
                }, {
                    key: "scale",
                    value: function (t) {
                        this.isNum(t) && (this.imgScale *= t, this.draw())
                    }
                }, {
                    key: "setOriginalSize",
                    value: function () {
                        var t = this.getFitSize();
                        this.imgScale = this.originalImg.width / t.w;
                        var e = this.cavW / 2 - this.originalImg.width / 2,
                            n = this.cavH / 2 - this.originalImg.height / 2;
                        this.imgScale > 1 ? (this.imgX = e * this.imgScale, this.imgY = n * this.imgScale) : (this.imgX = e / this.imgScale, this.imgY = n / this.imgScale), this.draw()
                    }
                }, {
                    key: "dstroy",
                    value: function () {
                        this.canvas.removeEventListener("mousedown", this, !1), this.canvas.removeEventListener("mouseout", this, !1), this.canvas.removeEventListener("mousewheel", this, !1), this.canvas.removeEventListener("mouseup", this, !1), this.canvas.removeEventListener("mousemove", this, !1), this.canvas = null, this.image = null
                    }
                }, {
                    key: "windowToCanvas",
                    value: function (t, e) {
                        var n = this.canvas.getBoundingClientRect();
                        return {
                            x: t - n.left - (n.width - this.cavW) / 2,
                            y: e - n.top - (n.height - this.cavH) / 2
                        }
                    }
                }, {
                    key: "clearCanvas",
                    value: function () {
                        this.ctx.clearRect(0, 0, this.cavW, this.cavH)
                    }
                }, {
                    key: "renderImage",
                    value: function (t, e, n, i) {
                        this.clearCanvas(), this.ctx.save(), this.ctx.fillStyle = "white", this.ctx.fill(), this.ctx.translate(t + n / 2, e + i / 2), this.ctx.rotate(this.angle), this.ctx.scale(this.isHRevert, this.isVRevert), this.ctx.drawImage(this.image, 0, 0, this.imageW, this.imageH, -n / 2, -i / 2, n, i), this.ctx.restore()
                    }
                }, {
                    key: "rotate",
                    value: function (t) {
                        this.angle += t, this.draw()
                    }
                }, {
                    key: "vRevert",
                    value: function () {
                        this.isVRevert *= -1, this.draw()
                    }
                }, {
                    key: "hRevert",
                    value: function () {
                        this.isHRevert *= -1, this.draw()
                    }
                }, {
                    key: "draw",
                    value: function () {
                        var t = this.getFitSize();
                        this.renderImage(this.imgX, this.imgY, t.w * this.imgScale, t.h * this.imgScale)
                    }
                }]), t
            }()
    }, function (t, e, n) {
        var i = n(13);
        i(i.P, "Array", {
            fill: n(26)
        }), n(31)("fill")
    }, function (t, e, n) {
        var i = n(0),
            o = n(1),
            r = n(2),
            s = n(20),
            u = n(24),
            a = function (t, e, n) {
                var c, h, f, l, v = t & a.F,
                    m = t & a.G,
                    p = t & a.S,
                    d = t & a.P,
                    g = t & a.B,
                    y = m ? i : p ? i[e] || (i[e] = {}) : (i[e] || {}).prototype,
                    w = m ? o : o[e] || (o[e] = {}),
                    x = w.prototype || (w.prototype = {});
                for (c in m && (n = e), n) f = ((h = !v && y && void 0 !== y[c]) ? y : n)[c], l = g && h ? u(f, i) : d && "function" == typeof f ? u(Function.call, f) : f, y && s(y, c, f, t & a.U), w[c] != f && r(w, c, l), d && x[c] != f && (x[c] = f)
            };
        i.core = o, a.F = 1, a.G = 2, a.S = 4, a.P = 8, a.B = 16, a.W = 32, a.U = 64, a.R = 128, t.exports = a
    }, function (t, e, n) {
        var i = n(15),
            o = n(16),
            r = n(18),
            s = Object.defineProperty;
        e.f = n(4) ? Object.defineProperty : function (t, e, n) {
            if (i(t), e = r(e, !0), i(n), o) try {
                return s(t, e, n)
            } catch (t) {}
            if ("get" in n || "set" in n) throw TypeError("Accessors not supported!");
            return "value" in n && (t[e] = n.value), t
        }
    }, function (t, e, n) {
        var i = n(3);
        t.exports = function (t) {
            if (!i(t)) throw TypeError(t + " is not an object!");
            return t
        }
    }, function (t, e, n) {
        t.exports = !n(4) && !n(5)((function () {
            return 7 != Object.defineProperty(n(17)("div"), "a", {
                get: function () {
                    return 7
                }
            }).a
        }))
    }, function (t, e, n) {
        var i = n(3),
            o = n(0).document,
            r = i(o) && i(o.createElement);
        t.exports = function (t) {
            return r ? o.createElement(t) : {}
        }
    }, function (t, e, n) {
        var i = n(3);
        t.exports = function (t, e) {
            if (!i(t)) return t;
            var n, o;
            if (e && "function" == typeof (n = t.toString) && !i(o = n.call(t))) return o;
            if ("function" == typeof (n = t.valueOf) && !i(o = n.call(t))) return o;
            if (!e && "function" == typeof (n = t.toString) && !i(o = n.call(t))) return o;
            throw TypeError("Can't convert object to primitive value")
        }
    }, function (t, e) {
        t.exports = function (t, e) {
            return {
                enumerable: !(1 & t),
                configurable: !(2 & t),
                writable: !(4 & t),
                value: e
            }
        }
    }, function (t, e, n) {
        var i = n(0),
            o = n(2),
            r = n(21),
            s = n(6)("src"),
            u = n(22),
            a = ("" + u).split("toString");
        n(1).inspectSource = function (t) {
            return u.call(t)
        }, (t.exports = function (t, e, n, u) {
            var c = "function" == typeof n;
            c && (r(n, "name") || o(n, "name", e)), t[e] !== n && (c && (r(n, s) || o(n, s, t[e] ? "" + t[e] : a.join(String(e)))), t === i ? t[e] = n : u ? t[e] ? t[e] = n : o(t, e, n) : (delete t[e], o(t, e, n)))
        })(Function.prototype, "toString", (function () {
            return "function" == typeof this && this[s] || u.call(this)
        }))
    }, function (t, e) {
        var n = {}.hasOwnProperty;
        t.exports = function (t, e) {
            return n.call(t, e)
        }
    }, function (t, e, n) {
        t.exports = n(7)("native-function-to-string", Function.toString)
    }, function (t, e) {
        t.exports = !1
    }, function (t, e, n) {
        var i = n(25);
        t.exports = function (t, e, n) {
            if (i(t), void 0 === e) return t;
            switch (n) {
                case 1:
                    return function (n) {
                        return t.call(e, n)
                    };
                case 2:
                    return function (n, i) {
                        return t.call(e, n, i)
                    };
                case 3:
                    return function (n, i, o) {
                        return t.call(e, n, i, o)
                    }
            }
            return function () {
                return t.apply(e, arguments)
            }
        }
    }, function (t, e) {
        t.exports = function (t) {
            if ("function" != typeof t) throw TypeError(t + " is not a function!");
            return t
        }
    }, function (t, e, n) {
        "use strict";
        var i = n(27),
            o = n(29),
            r = n(30);
        t.exports = function (t) {
            for (var e = i(this), n = r(e.length), s = arguments.length, u = o(s > 1 ? arguments[1] : void 0, n), a = s > 2 ? arguments[2] : void 0, c = void 0 === a ? n : o(a, n); c > u;) e[u++] = t;
            return e
        }
    }, function (t, e, n) {
        var i = n(28);
        t.exports = function (t) {
            return Object(i(t))
        }
    }, function (t, e) {
        t.exports = function (t) {
            if (null == t) throw TypeError("Can't call method on  " + t);
            return t
        }
    }, function (t, e, n) {
        var i = n(8),
            o = Math.max,
            r = Math.min;
        t.exports = function (t, e) {
            return (t = i(t)) < 0 ? o(t + e, 0) : r(t, e)
        }
    }, function (t, e, n) {
        var i = n(8),
            o = Math.min;
        t.exports = function (t) {
            return t > 0 ? o(i(t), 9007199254740991) : 0
        }
    }, function (t, e, n) {
        var i = n(32)("unscopables"),
            o = Array.prototype;
        null == o[i] && n(2)(o, i, {}), t.exports = function (t) {
            o[i][t] = !0
        }
    }, function (t, e, n) {
        var i = n(7)("wks"),
            o = n(6),
            r = n(0).Symbol,
            s = "function" == typeof r;
        (t.exports = function (t) {
            return i[t] || (i[t] = s && r[t] || (s ? r : o)("Symbol." + t))
        }).store = i
    }]).default
}));