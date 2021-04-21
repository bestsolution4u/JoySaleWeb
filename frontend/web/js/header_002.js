!(function(t) {
  function e(i) {
    if (n[i]) return n[i].exports;
    var r = (n[i] = { i: i, l: !1, exports: {} });
    return t[i].call(r.exports, r, r.exports, e), (r.l = !0), r.exports;
  }
  var n = {};
  (e.m = t),
    (e.c = n),
    (e.d = function(t, n, i) {
      e.o(t, n) ||
        Object.defineProperty(t, n, {
          configurable: !1,
          enumerable: !0,
          get: i
        });
    }),
    (e.n = function(t) {
      var n =
        t && t.__esModule
          ? function() {
              return t.default;
            }
          : function() {
              return t;
            };
      return e.d(n, "a", n), n;
    }),
    (e.o = function(t, e) {
      return Object.prototype.hasOwnProperty.call(t, e);
    }),
    (e.p = ""),
    e((e.s = 1));
})([
  function(t, e, n) {
    "use strict";
    function i(t, e) {
      if (!(t instanceof e))
        throw new TypeError("Cannot call a class as a function");
    }
    Object.defineProperty(e, "__esModule", { value: !0 });
    var r = (function() {
        function t(t, e) {
          for (var n = 0; n < e.length; n++) {
            var i = e[n];
            (i.enumerable = i.enumerable || !1),
              (i.configurable = !0),
              "value" in i && (i.writable = !0),
              Object.defineProperty(t, i.key, i);
          }
        }
        return function(e, n, i) {
          return n && t(e.prototype, n), i && t(e, i), e;
        };
      })(),
      o = (function() {
        function t() {
          var e = this,
            n =
              arguments.length > 0 && void 0 !== arguments[0]
                ? arguments[0]
                : {};
          i(this, t);
          var r = {
              duration: 330,
              delay: 0,
              easing: function(t) {
                return t;
              },
              transform: !0,
              opacity: !0,
              play: "rAF"
            },
            o = Object.assign({}, r, n);
          if (void 0 === o.element)
            throw new Error("Element must be provided.");
          if ("function" != typeof o.easing) {
            if (void 0 === o.easing.getRatio)
              throw new Error("Easing function must be provided.");
            o.easing = o.easing.getRatio;
          }
          (this.element_ = o.element),
            (this.first_ = { layout: null, opacity: 0 }),
            (this.last_ = { layout: null, opacity: 0 }),
            (this.invert_ = { x: 0, y: 0, sx: 1, sy: 1, a: 0 }),
            (this.start_ = 0),
            (this.duration_ = o.duration),
            (this.delay_ = o.delay),
            (this.easing_ = o.easing),
            (this.updateTransform_ = o.transform),
            (this.updateOpacity_ = o.opacity);
          var a = t.players_[o.play];
          if (void 0 === a) throw new Error("Unknown player type: " + o.play);
          var s = Object.keys(a),
            u = void 0;
          s.forEach(function(t) {
            (u = a[t]), (e[t] = u.bind(e));
          });
        }
        return (
          r(t, null, [
            {
              key: "extend",
              value: function(t, e) {
                void 0 === this.players_ && (this.players_ = {}),
                  void 0 !== this.players_[t] &&
                    console.warn("Player with name " + t + " already exists"),
                  void 0 === e.play_ &&
                    console.warn("Player does not contain a play_() function"),
                  (this.players_[t] = e);
              }
            },
            {
              key: "group",
              value: function(e) {
                if (!Array.isArray(e))
                  throw new Error("group() expects an array of objects.");
                return (
                  (e = e.map(function(e) {
                    return new t(e);
                  })),
                  {
                    flips_: e,
                    addClass: function(t) {
                      e.forEach(function(e) {
                        return e.addClass(t);
                      });
                    },
                    removeClass: function(t) {
                      e.forEach(function(e) {
                        return e.removeClass(t);
                      });
                    },
                    first: function() {
                      e.forEach(function(t) {
                        return t.first();
                      });
                    },
                    last: function(t) {
                      e.forEach(function(e, n) {
                        var i = t;
                        Array.isArray(t) && (i = t[n]),
                          void 0 !== i && e.element_.classList.add(i);
                      }),
                        e.forEach(function(t) {
                          return t.last();
                        });
                    },
                    invert: function() {
                      e.forEach(function(t) {
                        return t.invert();
                      });
                    },
                    play: function(t) {
                      void 0 === t && (t = window.performance.now()),
                        e.forEach(function(e) {
                          return e.play(t);
                        });
                    }
                  }
                );
              }
            },
            {
              key: "version",
              get: function() {
                return "@VERSION@";
              }
            }
          ]),
          r(t, [
            {
              key: "addClass",
              value: function(t) {
                "string" == typeof t && this.element_.classList.add(t);
              }
            },
            {
              key: "removeClass",
              value: function(t) {
                "string" == typeof t && this.element_.classList.remove(t);
              }
            },
            {
              key: "snapshot",
              value: function(t) {
                this.first(), this.last(t), this.invert();
              }
            },
            {
              key: "first",
              value: function() {
                (this.first_.layout = this.element_.getBoundingClientRect()),
                  (this.first_.opacity = parseFloat(
                    window.getComputedStyle(this.element_).opacity
                  ));
              }
            },
            {
              key: "last",
              value: function(t) {
                void 0 !== t && this.addClass(t),
                  (this.last_.layout = this.element_.getBoundingClientRect()),
                  (this.last_.opacity = parseFloat(
                    window.getComputedStyle(this.element_).opacity
                  ));
              }
            },
            {
              key: "invert",
              value: function() {
                var t = [];
                if (null === this.first_.layout)
                  throw new Error("You must call first() before invert()");
                if (null === this.last_.layout)
                  throw new Error("You must call last() before invert()");
                (this.invert_.x =
                  this.first_.layout.left - this.last_.layout.left),
                  (this.invert_.y =
                    this.first_.layout.top - this.last_.layout.top),
                  (this.invert_.sx =
                    this.first_.layout.width / this.last_.layout.width),
                  (this.invert_.sy =
                    this.first_.layout.height / this.last_.layout.height),
                  (this.invert_.a = this.last_.opacity - this.first_.opacity),
                  this.updateTransform_ &&
                    ((this.element_.style.transformOrigin = "0 0"),
                    (this.element_.style.transform =
                      "translate(" +
                      this.invert_.x +
                      "px, " +
                      this.invert_.y +
                      "px)\n           scale(" +
                      this.invert_.sx +
                      ", " +
                      this.invert_.sy +
                      ")"),
                    t.push("transform")),
                  this.updateOpacity_ &&
                    ((this.element_.style.opacity = this.first_.opacity),
                    t.push("opacity")),
                  (this.element_.style.willChange = t.join(","));
              }
            },
            {
              key: "play",
              value: function(t) {
                if (null === this.invert_)
                  throw new Error("invert() must be called before play()");
                if (void 0 === this.play_)
                  throw new Error("No player specified.");
                this.play_(t);
              }
            },
            {
              key: "fire_",
              value: function(t) {
                var e =
                    arguments.length > 1 && void 0 !== arguments[1]
                      ? arguments[1]
                      : null,
                  n =
                    !(arguments.length > 2 && void 0 !== arguments[2]) ||
                    arguments[2],
                  i =
                    !(arguments.length > 3 && void 0 !== arguments[3]) ||
                    arguments[3],
                  r = new CustomEvent(t, {
                    detail: e,
                    bubbles: n,
                    cancelable: i
                  });
                this.element_.dispatchEvent(r);
              }
            },
            {
              key: "clamp_",
              value: function(t) {
                var e =
                    arguments.length > 1 && void 0 !== arguments[1]
                      ? arguments[1]
                      : Number.NEGATIVE_INFINITY,
                  n =
                    arguments.length > 2 && void 0 !== arguments[2]
                      ? arguments[2]
                      : Number.POSITIVE_INFINITY;
                return Math.min(n, Math.max(e, t));
              }
            },
            {
              key: "cleanUpAndFireEvent_",
              value: function() {
                this.removeTransformsAndOpacity_(),
                  this.resetFirstLastAndInvertValues_(),
                  this.fire_("flipComplete");
              }
            },
            {
              key: "removeTransformsAndOpacity_",
              value: function() {
                (this.element_.style.transformOrigin = null),
                  (this.element_.style.transform = null),
                  (this.element_.style.opacity = null),
                  (this.element_.style.willChange = null);
              }
            },
            {
              key: "resetFirstLastAndInvertValues_",
              value: function() {
                (this.first_.layout = null),
                  (this.first_.opacity = 0),
                  (this.last_.layout = null),
                  (this.last_.opacity = 0),
                  (this.invert_.x = 0),
                  (this.invert_.y = 0),
                  (this.invert_.sx = 1),
                  (this.invert_.sy = 1),
                  (this.invert_.a = 0);
              }
            }
          ]),
          t
        );
      })();
    e.default = o;
  },
  function(t, e, n) {
    "use strict";
    function i(t) {
      return t && t.__esModule ? t : { default: t };
    }
    var r = n(2),
      o = i(r),
      a = n(0),
      s = i(a);
    (window.modalMorph = o.default), (window.FLIP = s.default);
  },
  function(t, e, n) {
    "use strict";
    function i(t) {
      return t && t.__esModule ? t : { default: t };
    }
    function r(t) {
      function e() {
        if (!m) {
          (m = !0), h.overlay.classList.add("mm--visible");
          var t = i({ element: h.overlay, easing: c, duration: 100 }, function(
            t
          ) {
            return t.classList.add("mm--opened");
          });
          h.popup.style.backgroundColor =
            "function" == typeof h.bgColor ? h.bgColor() : h.bgColor;
          var e = l(),
            n = i(
              { element: h.popup, easing: c, delay: 50, duration: 350 },
              function(t) {
                t.classList.add("mm__popup--opened"),
                  document.head.removeChild(e);
              }
            ),
            a = i(
              { element: h.content, easing: c, delay: 150, duration: 600 },
              function(t) {
                return t.classList.add("mm__content--opened");
              }
            );
          t.play(),
            n.play(),
            a.play(),
            r([t, n, a], function() {
              _.activate(), h.content.addEventListener("click", o);
            });
        }
      }
      function n() {
        if (m) {
          (m = !1), h.content.removeEventListener("click", o);
          var t = i({ element: h.content, duration: 300 }, function(t) {
            return t.classList.remove("mm__content--opened");
          });
          t.play(),
            r([t], function() {
              var t = i(
                  { element: h.overlay, easing: d, duration: 400 },
                  function(t) {
                    return t.classList.remove("mm--opened");
                  }
                ),
                e = void 0,
                n = i({ element: h.popup, easing: d, duration: 300 }, function(
                  t
                ) {
                  t.classList.remove("mm__popup--opened"), (e = l());
                });
              t.play(),
                n.play(),
                r([t, n], function() {
                  document.head.removeChild(e),
                    h.overlay.classList.remove("mm--visible");
                });
            });
        }
      }
      function i(t, e) {
        var n = new a.default(t);
        return n.first(), e(n.element_), n.last(), n.invert(), n;
      }
      function r(t, e) {
        function n(t) {
          t.target === i && (i.removeEventListener("flipComplete", n), e());
        }
        var i = null,
          r = 0;
        t.forEach(function(t) {
          var e = t.delay_,
            n = t.duration_,
            o = t.element_,
            a = e + n;
          a > r && ((r = a), (i = o));
        }),
          i.addEventListener("flipComplete", n);
      }
      function o(t) {
        function e(t) {
          return t.classList.contains("mm__close");
        }
        var n = t.target;
        (e(n) || e(n.parentElement)) && _.deactivate();
      }
      function s() {
        return (
          (h.trigger && window.getComputedStyle(h.trigger).backgroundColor) ||
          ""
        );
      }
      function l() {
        var e = h.trigger.getBoundingClientRect(),
          n = ["top", "left", "height", "width"]
            .map(function(t) {
              return t + ": " + e[t] + "px;";
            })
            .join(" "),
          i = "#" + t + " .mm__popup { " + n + " }",
          r = document.createElement("style");
        return (
          (r.type = "text/css"),
          r.appendChild(document.createTextNode(i)),
          document.head.appendChild(r),
          r
        );
      }
      function c(t) {
        return --t * t * t * t * t + 1;
      }
      function d(t) {
        return (t *= 2) < 1
          ? 0.5 * t * t * t * t * t
          : 0.5 * ((t -= 2) * t * t * t * t + 2);
      }
      var f =
          arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
        v = document.getElementById(t);
      if (!v) throw new Error("A valid modal id must be provided.");
      if (!f.trigger)
        throw new Error("A valid trigger element must be provided.");
      var p = {
          overlay: v,
          popup: v.querySelector(".mm__popup"),
          content: v.querySelector(".mm__content"),
          title: v.querySelector(".mm__title"),
          bgColor: s
        },
        y = { open: e, close: n },
        h = Object.assign({}, p, f, y),
        m = !1,
        _ = (0, u.default)(h.content, {
          onDeactivate: n,
          clickOutsideDeactivates: !0
        });
      return h;
    }
    Object.defineProperty(e, "__esModule", { value: !0 }), (e.default = r);
    var o = n(3),
      a = i(o),
      s = n(5),
      u = i(s);
  },
  function(t, e, n) {
    "use strict";
    function i(t) {
      return t && t.__esModule ? t : { default: t };
    }
    Object.defineProperty(e, "__esModule", { value: !0 });
    var r = n(0),
      o = i(r),
      a = n(4),
      s = i(a);
    o.default.extend("rAF", s.default), (e.default = o.default);
  },
  function(t, e, n) {
    "use strict";
    Object.defineProperty(e, "__esModule", { value: !0 }),
      (e.default = {
        play_: function(t) {
          (this.start_ =
            void 0 === t
              ? window.performance.now() + this.delay_
              : t + this.delay_),
            requestAnimationFrame(this.update_);
        },
        update_: function() {
          var t = (window.performance.now() - this.start_) / this.duration_;
          t = this.clamp_(t, 0, 1);
          var e = this.easing_(t),
            n = {
              x: this.invert_.x * (1 - e),
              y: this.invert_.y * (1 - e),
              sx: this.invert_.sx + (1 - this.invert_.sx) * e,
              sy: this.invert_.sy + (1 - this.invert_.sy) * e,
              a: this.first_.opacity + this.invert_.a * e
            };
          this.updateTransform_ &&
            (this.element_.style.transform =
              "translate(" +
              n.x +
              "px, " +
              n.y +
              "px)\n         scale(" +
              n.sx +
              ", " +
              n.sy +
              ")"),
            this.updateOpacity_ && (this.element_.style.opacity = n.a),
            t < 1
              ? requestAnimationFrame(this.update_)
              : this.cleanUpAndFireEvent_();
        }
      });
  },
  function(t, e, n) {
    "use strict";
    function i(t, e) {
      function n(t) {
        if (!b) {
          var e = {
            onActivate:
              t && void 0 !== t.onActivate ? t.onActivate : C.onActivate
          };
          return (
            (b = !0),
            (x = !1),
            (E = document.activeElement),
            e.onActivate && e.onActivate(),
            c(),
            L
          );
        }
      }
      function i(t) {
        if (b) {
          var e = {
            returnFocus:
              t && void 0 !== t.returnFocus
                ? t.returnFocus
                : C.returnFocusOnDeactivate,
            onDeactivate:
              t && void 0 !== t.onDeactivate ? t.onDeactivate : C.onDeactivate
          };
          return (
            d(),
            e.onDeactivate && e.onDeactivate(),
            e.returnFocus &&
              setTimeout(function() {
                o(E);
              }, 0),
            (b = !1),
            (x = !1),
            this
          );
        }
      }
      function u() {
        !x && b && ((x = !0), d());
      }
      function l() {
        x && b && ((x = !1), c());
      }
      function c() {
        if (b)
          return (
            s && s.pause(),
            (s = L),
            g(),
            o(v()),
            document.addEventListener("focus", h, !0),
            document.addEventListener("click", y, !0),
            document.addEventListener("mousedown", p, !0),
            document.addEventListener("touchstart", p, !0),
            document.addEventListener("keydown", m, !0),
            L
          );
      }
      function d() {
        if (b && s === L)
          return (
            document.removeEventListener("focus", h, !0),
            document.removeEventListener("click", y, !0),
            document.removeEventListener("mousedown", p, !0),
            document.removeEventListener("touchstart", p, !0),
            document.removeEventListener("keydown", m, !0),
            (s = null),
            L
          );
      }
      function f(t) {
        var e = C[t],
          n = e;
        if (!e) return null;
        if ("string" == typeof e && !(n = document.querySelector(e)))
          throw new Error("`" + t + "` refers to no known node");
        if ("function" == typeof e && !(n = e()))
          throw new Error("`" + t + "` did not return a node");
        return n;
      }
      function v() {
        var t;
        if (
          !(t =
            null !== f("initialFocus")
              ? f("initialFocus")
              : k.contains(document.activeElement)
                ? document.activeElement
                : w[0] || f("fallbackFocus"))
        )
          throw new Error(
            "You can't have a focus-trap without at least one focusable element"
          );
        return t;
      }
      function p(t) {
        C.clickOutsideDeactivates &&
          !k.contains(t.target) &&
          i({ returnFocus: !1 });
      }
      function y(t) {
        C.clickOutsideDeactivates ||
          k.contains(t.target) ||
          (t.preventDefault(), t.stopImmediatePropagation());
      }
      function h(t) {
        k.contains(t.target) ||
          (t.preventDefault(),
          t.stopImmediatePropagation(),
          "function" == typeof t.target.blur && t.target.blur());
      }
      function m(t) {
        ("Tab" !== t.key && 9 !== t.keyCode) || _(t),
          !1 !== C.escapeDeactivates && r(t) && i();
      }
      function _(t) {
        t.preventDefault(), g();
        var e = w.indexOf(t.target),
          n = w[w.length - 1],
          i = w[0];
        return t.shiftKey
          ? o(t.target === i || -1 === w.indexOf(t.target) ? n : w[e - 1])
          : t.target === n
            ? o(i)
            : void o(w[e + 1]);
      }
      function g() {
        w = a(k);
      }
      var w = [],
        E = null,
        b = !1,
        x = !1,
        k = "string" == typeof t ? document.querySelector(t) : t,
        C = e || {};
      (C.returnFocusOnDeactivate =
        !e ||
        void 0 === e.returnFocusOnDeactivate ||
        e.returnFocusOnDeactivate),
        (C.escapeDeactivates =
          !e || void 0 === e.escapeDeactivates || e.escapeDeactivates);
      var L = { activate: n, deactivate: i, pause: u, unpause: l };
      return L;
    }
    function r(t) {
      return "Escape" === t.key || "Esc" === t.key || 27 === t.keyCode;
    }
    function o(t) {
      t &&
        t.focus &&
        (t.focus(), "input" === t.tagName.toLowerCase() && t.select());
    }
    var a = n(6),
      s = null;
    t.exports = i;
  },
  function(t, e, n) {
    "use strict";
    function i() {
      function t(n, i) {
        if (n === document.documentElement) return !1;
        for (var r = 0, o = e.length; r < o; r++)
          if (e[r][0] === n) return e[r][1];
        i = i || window.getComputedStyle(n);
        var a = !1;
        return (
          "none" === i.display
            ? (a = !0)
            : n.parentNode && (a = t(n.parentNode)),
          e.push([n, a]),
          a
        );
      }
      var e = [];
      return function(e) {
        if (e === document.documentElement) return !1;
        var n = window.getComputedStyle(e);
        return !!t(e, n) || "hidden" === n.visibility;
      };
    }
    t.exports = function(t) {
      for (
        var e,
          n,
          r = [],
          o = [],
          a = i(),
          s = [
            "input",
            "select",
            "a[href]",
            "textarea",
            "button",
            "[tabindex]"
          ],
          u = t.querySelectorAll(s),
          l = 0,
          c = u.length;
        l < c;
        l++
      )
        (e = u[l]),
          (n = parseInt(e.getAttribute("tabindex"), 10) || e.tabIndex) < 0 ||
            ("INPUT" === e.tagName && "hidden" === e.type) ||
            e.disabled ||
            a(e) ||
            (0 === n ? r.push(e) : o.push({ tabIndex: n, node: e }));
      var d = o
        .sort(function(t, e) {
          return t.tabIndex - e.tabIndex;
        })
        .map(function(t) {
          return t.node;
        });
      return Array.prototype.push.apply(d, r), d;
    };
  }
]);
//# sourceMappingURL=header.min.js.map
