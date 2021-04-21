
!(function(e) {
  "use strict";
  var n = function(n, t, r) {
    function o(e) {
      return a.body
        ? e()
        : void setTimeout(function() {
            o(e);
          });
    }
    function i() {
      u.addEventListener && u.removeEventListener("load", i),
        (u.media = r || "all");
    }
    var c,
      a = e.document,
      u = a.createElement("link");
    if (t) c = t;
    else {
      var f = (a.body || a.getElementsByTagName("head")[0]).childNodes;
      c = f[f.length - 1];
    }
    var s = a.styleSheets;
    (u.rel = "stylesheet"),
      (u.href = n),
      (u.media = "only x"),
      o(function() {
        c.parentNode.insertBefore(u, t ? c : c.nextSibling);
      });
    var l = function(e) {
      for (var n = u.href, t = s.length; t--; ) if (s[t].href === n) return e();
      setTimeout(function() {
        l(e);
      });
    };
    return (
      u.addEventListener && u.addEventListener("load", i),
      (u.onloadcssdefined = l),
      l(i),
      u
    );
  };
  "undefined" != typeof exports ? (exports.loadCSS = n) : (e.loadCSS = n);
})(
  "undefined" != typeof global ? global : this
) /*! loadCSS rel=preload polyfill. [c]2017 Filament Group, Inc. MIT License */,
  (function(e) {
    if (e.loadCSS) {
      var n = (loadCSS.relpreload = {});
      if (
        ((n.support = function() {
          try {
            return e.document.createElement("link").relList.supports("preload");
          } catch (n) {
            return !1;
          }
        }),
        (n.poly = function() {
          for (
            var n = e.document.getElementsByTagName("link"), t = 0;
            t < n.length;
            t++
          ) {
            var r = n[t];
            "preload" === r.rel &&
              "style" === r.getAttribute("as") &&
              (e.loadCSS(r.href, r, r.getAttribute("media")), (r.rel = null));
          }
        }),
        !n.support())
      ) {
        n.poly();
        var t = e.setInterval(n.poly, 300);
        e.addEventListener &&
          e.addEventListener("load", function() {
            n.poly(), e.clearInterval(t);
          }),
          e.attachEvent &&
            e.attachEvent("onload", function() {
              e.clearInterval(t);
            });
      }
    }
  })(this),
  !(function() {
    "use strict";
    "function" != typeof Object.assign &&
      (Object.assign = function(e) {
        if (null == e)
          throw new TypeError("Cannot convert undefined or null to object");
        e = Object(e);
        for (var n = 1; n < arguments.length; n++) {
          var t = arguments[n];
          if (null != t)
            for (var r in t)
              Object.prototype.hasOwnProperty.call(t, r) && (e[r] = t[r]);
        }
        return e;
      });
    try {
      new window.CustomEvent("test");
    } catch (e) {
      var n = function(e, n) {
        var t;
        return (
          (n = n || { bubbles: !1, cancelable: !1, detail: void 0 }),
          (t = document.createEvent("CustomEvent")),
          t.initCustomEvent(e, n.bubbles, n.cancelable, n.detail),
          t
        );
      };
      (n.prototype = window.Event.prototype), (window.CustomEvent = n);
    }
  })(),
  !(function(e, n, t) {
    "use strict";
    var r = {};
    (e.gc = r),
      (r.once = function(e, n) {
        var t;
        return function() {
          return e && ((t = e.apply(n || this, arguments)), (e = null)), t;
        };
      }),
      (r.debounce = function(e, n) {
        var t;
        return function() {
          var r = this,
            o = arguments,
            i = function() {
              (t = null), e.apply(r, o);
            };
          clearTimeout(t), (t = setTimeout(i, n));
        };
      });
    var o = {};
    (r.addKeyHandler = function(e, n, t, r) {
      var i = [e, r];
      o.hasOwnProperty(n)
        ? o[n].handlers.push(i)
        : (o[n] = { keyCode: t, handlers: [i] });
    }),
      (r.removeKeyHandler = function(e, n) {
        if (n in o)
          for (var t = o[n].handlers, r = t.length - 1; r >= 0; r--)
            if (t[r][0] === e) {
              t.splice(r);
              break;
            }
      }),
      (r.fireNextKeyHandler = function(e) {
        if (!(e in o)) return !1;
        var n = o[e].handlers;
        if (n.length) {
          var r = n[n.length - 1];
          return (
            r[1](),
            t.log('Firing handler "', r[0], '" for keydown event "', e, '"'),
            !0
          );
        }
        return !1;
      }),
      e.addEventListener("keydown", function(t) {
        t = t || e.event;
        var i = t.keyCode,
          c =
            (i > 47 && i < 58) ||
            32 == i ||
            13 == i ||
            (i > 64 && i < 91) ||
            (i > 95 && i < 112) ||
            (i > 185 && i < 193) ||
            (i > 218 && i < 223);
        if (c) {
          var a = !!n.activeElement && n.activeElement.tagName.toLowerCase();
          if ("input" === a || "textarea" === a) return;
        }
        for (var u in o)
          (("key" in t && t.key == u) || t.keyCode == o[u].keyCode) &&
            (t.preventDefault(), r.fireNextKeyHandler(u));
      }),
      (r.width = e.innerWidth);
    var i = {};
    e.addEventListener(
      "resize",
      r.debounce(function() {
        var n = e.innerWidth,
          t = r.width;
        r.width = n;
        for (var o in i) i[o](t, n);
      }, 200)
    ),
      (r.addResizeCallback = function(e, n) {
        i.hasOwnProperty(e) || (i[e] = n);
      }),
      (r.removeResizeCallback = function(e) {
        delete i[e];
      }),
      (r.addEventListenerOnce = function(e, n, t) {
        e.addEventListener(n, function r(e) {
          return e.currentTarget.removeEventListener(e.type, r), t(e);
        });
      }),
      (r.iOS = !!navigator.userAgent.match(/(iPad|iPhone|iPod)/g));
  })(window, document, console),
  (function(e) {
    var n;
    "undefined" != typeof window
      ? (n = window)
      : "undefined" != typeof self && (n = self),
      (n.inViewport = e());
  })(function() {
    return (function e(n, t, r) {
      function o(c, a) {
        if (!t[c]) {
          if (!n[c]) {
            var u = "function" == typeof require && require;
            if (!a && u) return u(c, !0);
            if (i) return i(c, !0);
            throw ((u = Error("Cannot find module '" + c + "'")),
            (u.code = "MODULE_NOT_FOUND"),
            u);
          }
          (u = t[c] = { exports: {} }),
            n[c][0].call(
              u.exports,
              function(e) {
                var t = n[c][1][e];
                return o(t ? t : e);
              },
              u,
              u.exports,
              e,
              n,
              t,
              r
            );
        }
        return t[c].exports;
      }
      for (
        var i = "function" == typeof require && require, c = 0;
        c < r.length;
        c++
      )
        o(r[c]);
      return o;
    })(
      {
        1: [
          function(e, n, t) {
            (function(e) {
              function t(e, n, t) {
                e.attachEvent
                  ? e.attachEvent("on" + n, t)
                  : e.addEventListener(n, t, !1);
              }
              function r(e, n, t) {
                var r;
                return function() {
                  var o = this,
                    i = arguments,
                    c = t && !r;
                  clearTimeout(r),
                    (r = setTimeout(function() {
                      (r = null), t || e.apply(o, i);
                    }, n)),
                    c && e.apply(o, i);
                };
              }
              function o(n) {
                function o(e, n, t) {
                  return {
                    watch: function() {
                      s.add(e, n, t);
                    },
                    dispose: function() {
                      s.remove(e);
                    }
                  };
                }
                function a(t, r) {
                  if (
                    !(
                      f(e.document.documentElement, t) &&
                      f(e.document.documentElement, n) &&
                      t.offsetWidth &&
                      t.offsetHeight
                    )
                  )
                    return !1;
                  var o,
                    i,
                    c,
                    a,
                    u = t.getBoundingClientRect();
                  return (
                    n === e.document.body
                      ? ((o = -r),
                        (i = -r),
                        (c = e.document.documentElement.clientWidth + r),
                        (a = e.document.documentElement.clientHeight + r))
                      : ((a = n.getBoundingClientRect()),
                        (o = a.top - r),
                        (i = a.left - r),
                        (c = a.right + r),
                        (a = a.bottom + r)),
                    u.right >= i && u.left <= c && u.bottom >= o && u.top <= a
                  );
                }
                var s = i(),
                  l = n === e.document.body ? e : n,
                  d = r(
                    s.checkAll(function(e, n, t) {
                      a(e, n) && (s.remove(e), t(e));
                    }),
                    15
                  );
                return (
                  t(l, "scroll", d),
                  l === e && t(e, "resize", d),
                  u && c(s, n, d),
                  setInterval(d, 150),
                  {
                    container: n,
                    isInViewport: function(e, n, t) {
                      return t ? ((e = o(e, n, t)), e.watch(), e) : a(e, n);
                    }
                  }
                );
              }
              function i() {
                function e(e) {
                  for (var n = t.length - 1; 0 <= n; n--)
                    if (t[n][0] === e) return n;
                  return -1;
                }
                function n(n) {
                  return -1 !== e(n);
                }
                var t = [];
                return {
                  add: function(e, r, o) {
                    n(e) || t.push([e, r, o]);
                  },
                  remove: function(n) {
                    (n = e(n)), -1 !== n && t.splice(n, 1);
                  },
                  isWatched: n,
                  checkAll: function(e) {
                    return function() {
                      for (var n = t.length - 1; 0 <= n; n--)
                        e.apply(this, t[n]);
                    };
                  }
                };
              }
              function c(e, n, t) {
                function r(n) {
                  return (
                    (n = c.call(
                      [],
                      Array.prototype.slice.call(n.addedNodes),
                      n.target
                    )),
                    0 < i.call(n, e.isWatched).length
                  );
                }
                var o = new MutationObserver(function(e) {
                    !0 === e.some(r) && setTimeout(t, 0);
                  }),
                  i = Array.prototype.filter,
                  c = Array.prototype.concat;
                o.observe(n, { childList: !0, subtree: !0, attributes: !0 });
              }
              n.exports = function(n, t, r) {
                var i = e.document.body;
                (void 0 !== t && "function" != typeof t) || ((r = t), (t = {})),
                  (i = t.container || i),
                  (t = t.offset || 0);
                for (var c = 0; c < a.length; c++)
                  if (a[c].container === i) return a[c].isInViewport(n, t, r);
                return a[a.push(o(i)) - 1].isInViewport(n, t, r);
              };
              var a = [],
                u = "function" == typeof e.MutationObserver,
                f = e.document.documentElement.compareDocumentPosition
                  ? function(e, n) {
                      return !!(16 & e.compareDocumentPosition(n));
                    }
                  : e.document.documentElement.contains
                  ? function(e, n) {
                      return e !== n && !!e.contains && e.contains(n);
                    }
                  : function(e, n) {
                      for (; (n = n.parentNode); ) if (n === e) return !0;
                      return !1;
                    };
            }.call(
              this,
              "undefined" != typeof global
                ? global
                : "undefined" != typeof self
                ? self
                : "undefined" != typeof window
                ? window
                : {}
            ));
          },
          {}
        ]
      },
      {},
      [1]
    )(1);
  }),
  !(function(e) {
    "use strict";
    var n = e.gc,
      t = e.inViewport,
      r = e.innerHeight;
    n.whenNearViewport = function(e, n, o) {
      o = o || r;
      var i = t(e, { offset: o }, function() {
        i.dispose(), n();
      });
    };
  })(window),
  !(function(e, n) {
    "function" == typeof define && define.amd
      ? define([], n)
      : "undefined" != typeof module && module.exports
      ? (module.exports = n())
      : (e.lscache = n());
  })(this, function() {
    function e() {
      var e = "__lscachetest__",
        t = e;
      if (void 0 !== d) return d;
      try {
        c(e, t), a(e), (d = !0);
      } catch (r) {
        d = !!n(r);
      }
      return d;
    }
    function n(e) {
      return !!(
        (e && "QUOTA_EXCEEDED_ERR" === e.name) ||
        "NS_ERROR_DOM_QUOTA_REACHED" === e.name ||
        "QuotaExceededError" === e.name
      );
    }
    function t() {
      return void 0 === h && (h = null != window.JSON), h;
    }
    function r(e) {
      return e + v;
    }
    function o() {
      return Math.floor(new Date().getTime() / g);
    }
    function i(e) {
      return localStorage.getItem(p + w + e);
    }
    function c(e, n) {
      localStorage.removeItem(p + w + e), localStorage.setItem(p + w + e, n);
    }
    function a(e) {
      localStorage.removeItem(p + w + e);
    }
    function u(e) {
      for (
        var n = new RegExp("^" + p + w + "(.*)"), t = localStorage.length - 1;
        t >= 0;
        --t
      ) {
        var o = localStorage.key(t);
        (o = o && o.match(n)),
          (o = o && o[1]),
          o && o.indexOf(v) < 0 && e(o, r(o));
      }
    }
    function f(e) {
      var n = r(e);
      a(e), a(n);
    }
    function s(e) {
      var n = r(e),
        t = i(n);
      if (t) {
        var c = parseInt(t, m);
        if (o() >= c) return a(e), a(n), !0;
      }
    }
    function l(e, n) {
      E &&
        "console" in window &&
        "function" == typeof window.console.warn &&
        (window.console.warn("lscache - " + e),
        n && window.console.warn("lscache - The error was: " + n.message));
    }
    var d,
      h,
      p = "lscache-",
      v = "-cacheexpiration",
      m = 10,
      g = 6e4,
      y = Math.floor(864e13 / g),
      w = "",
      E = !1,
      b = {
        set: function(s, d, h) {
          if (e()) {
            if ("string" != typeof d) {
              if (!t()) return;
              try {
                d = JSON.stringify(d);
              } catch (p) {
                return;
              }
            }
            try {
              c(s, d);
            } catch (p) {
              if (!n(p))
                return void l("Could not add item with key '" + s + "'", p);
              var v,
                g = [];
              u(function(e, n) {
                var t = i(n);
                (t = t ? parseInt(t, m) : y),
                  g.push({ key: e, size: (i(e) || "").length, expiration: t });
              }),
                g.sort(function(e, n) {
                  return n.expiration - e.expiration;
                });
              for (var w = (d || "").length; g.length && w > 0; )
                (v = g.pop()),
                  l("Cache is full, removing item with key '" + s + "'"),
                  f(v.key),
                  (w -= v.size);
              try {
                c(s, d);
              } catch (p) {
                return void l(
                  "Could not add item with key '" +
                    s +
                    "', perhaps it's too big?",
                  p
                );
              }
            }
            h ? c(r(s), (o() + h).toString(m)) : a(r(s));
          }
        },
        get: function(n) {
          if (!e()) return null;
          if (s(n)) return null;
          var r = i(n);
          if (!r || !t()) return r;
          try {
            return JSON.parse(r);
          } catch (o) {
            return r;
          }
        },
        remove: function(n) {
          e() && f(n);
        },
        supported: function() {
          return e();
        },
        flush: function() {
          e() &&
            u(function(e) {
              f(e);
            });
        },
        flushExpired: function() {
          e() &&
            u(function(e) {
              s(e);
            });
        },
        setBucket: function(e) {
          w = e;
        },
        resetBucket: function() {
          w = "";
        },
        enableWarnings: function(e) {
          E = e;
        }
      };
    return b;
  }),
  !(function() {
    "use strict";
    (gc.scripts = {}),
      (gc.load = function(e, n) {
        if (2 !== arguments.length)
          return void console.error("2 arguments required for gc.load");
        if (gc.scripts.hasOwnProperty(e)) return gc.scripts[e];
        var t = new Promise(function(t, r) {
          var o = !1,
            i = document.getElementsByTagName("head")[0],
            c = document.createElement("script");
          (c.src =
            "~/" === n.substring(0, 2)
              ? "/wp-content/themes/timber-gc/" + n.substring(2)
              : n),
            (c.async = !0),
            (c.onload = c.onreadystatechange = function() {
              o ||
                (this.readyState &&
                  "loaded" !== this.readyState &&
                  "complete" !== this.readyState) ||
                ((o = !0),
                t(this),
                (c.onload = c.onreadystatechange = null),
                i && c.parentNode && i.removeChild(c));
            }),
            (c.onerror = c.onabort = function() {
              console.log('Failed to load "' + e + '" script'), r();
            }),
            i.appendChild(c);
        });
        return (gc.scripts[e] = t), t;
      }),
      !(function() {
        function e(e) {
          var n,
            t = new Promise(function(e, t) {
              n = e;
            });
          return (e.resolve = n), t;
        }
        var n = ["jquery"],
          t = {};
        n.forEach(function(n) {
          (t[n] = {}), (gc.scripts[n] = e(t[n]));
        }),
          (gc.scriptHasLoaded = function(e) {
            t.hasOwnProperty(e) && (t[e].resolve(), delete t[e]);
          });
      })(),
      (gc.loadJSON = function(e) {
        return new Promise(function(n, t) {
          e =
            "~/" === e.substring(0, 2)
              ? "/wp-content/themes/timber-gc/" + e.substring(2)
              : e;
          var r = new XMLHttpRequest();
          r.open("GET", e, !0),
            (r.onload = function() {
              if (r.status >= 200 && r.status < 400)
                try {
                  var o = JSON.parse(r.responseText);
                  n(o);
                } catch (i) {
                  t("Unable to parse response as JSON: " + e);
                }
              else t("Server returned an error: " + e);
            }),
            (r.onerror = function(n) {
              t("Unable to connect to server: " + e);
            }),
            r.send();
        });
      }),
      (gc.loadCachedJSON = function(e, n, t) {
        function r(e) {
          return (
            t.cacheBreakingParameter &&
              (e +=
                (e.indexOf("?") === -1 ? "?" : "&") +
                t.cacheBreakingParameter +
                "=" +
                Math.random()),
            e
          );
        }
        function o(n) {
          lscache.set("gc-json-" + e, n, t.cacheTime);
        }
        function i(n, t) {
          if (!c && !a) {
            a = !0;
            var r = lscache.get("gc-json-" + e);
            null !== r
              ? (console.log(e + " > " + t + " > Got JSON from cache"),
                (c = !0),
                n(r))
              : console.error(
                  e + " > " + t + " > Unable to get JSON from cache"
                );
          }
        }
        var c = !1,
          a = !1,
          t = t || {},
          u = {
            cacheTime: 43200,
            fallbackTime: 3,
            maxWaitTime: 10,
            cacheBreakingParameter: "v"
          };
        for (var f in u) t.hasOwnProperty(f) || (t[f] = u[f]);
        return (
          (n = r(n)),
          new Promise(function(e, r) {
            gc
              .loadJSON(n)
              .then(function(n) {
                o(n), c || ((c = !0), e(n));
              })
              ["catch"](function(n) {
                i(e, n);
              }),
              setTimeout(function() {
                i(e, "No response in 3 seconds");
              }, 1e3 * t.fallbackTime),
              setTimeout(function() {
                c ||
                  ((c = !0), r("No response in " + t.maxWaitTime + " seconds"));
              }, 1e3 * t.maxWaitTime);
          })
        );
      });
  })(),
  !(function(e, n) {
    "use strict";
    function t(e, t) {
      var o = "; expires=Fri, 31 Dec 9999 23:59:59 GMT",
        i = "; domain=" + r(),
        c = "; path=/";
      n.cookie =
        encodeURIComponent(e) + "=" + encodeURIComponent(t) + o + i + c;
    }
    function r() {
      var n = e.location.hostname;
      return n.substring(n.lastIndexOf(".", n.lastIndexOf(".") - 1) + 1);
    }
    !(function() {
      n.cookie.indexOf("gc_first_page_url=") === -1 &&
        (t("gc_first_page_url", e.location.href),
        t("gc_first_page_referrer", n.referrer),
        t("gc_first_page_timestamp", +new Date()));
    })(),
      (gc.loadAndInitMunchkin = function(e) {
        gc.load("munchkin", "//munchkin.marketo.net/munchkin.js").then(
          function() {
            gc.isMunchkinInit ||
              (Munchkin.init("405-DGE-019", { asyncOnly: !0 }),
              (gc.isMunchkinInit = !0)),
              e && e();
          }
        );
      }),
      (gc.trackPageview = function(e) {
        (e = e || null), gc.trackLeadEvent({ event: "Pageview", type: e });
      });
    var o = [];
    (gc.trackLeadEvent = function(e) {
      o.push(e);
    }),
      (gc.getLeadEvents = function() {
        return o;
      });
  })(window, document);
//# sourceMappingURL=../sourcemaps/inline-head.min.js.map

gc.wordpressSitePath = "/about/";
