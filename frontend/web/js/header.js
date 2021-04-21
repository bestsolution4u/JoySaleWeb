"use strict";
(gc.openModal = function(e, t, n) {
  // From Tween.js (MIT license)
  // @see https://github.com/tweenjs/tween.js/blob/master/src/Tween.js
  function o(e) {
    return --e * e * e * e * e + 1;
  }
  function s(e) {
    return (e *= 2) < 1
      ? 0.5 * e * e * e * e * e
      : 0.5 * ((e -= 2) * e * e * e * e + 2);
  }
  function c() {
    var t = e.getBoundingClientRect();
    (v.style.top = t.top + "px"),
      (v.style.left = t.left + "px"),
      (v.style.height = t.height + "px"),
      (v.style.width = t.width + "px");
  }
  function a(e) {
    e.stopPropagation();
  }
  function i() {
    r(l);
  }
  function r(e) {
    m.classList.add("overlayed"),
      (v.style.backgroundColor =
        "function" == typeof n.color ? n.color() : n.color),
      c();
    var t = FLIP.group([
      { element: m, easing: o, delay: 0, duration: 100 },
      { element: v, easing: o, delay: 50, duration: 400 }
    ]);
    t.first(),
      (v.style.top = "function" == typeof n.top ? n.top() : n.top),
      t.last("flipped"),
      t.invert(),
      t.play(),
      gc.addEventListenerOnce(v, "flipComplete", e);
  }
  function l() {
    var e = FLIP.group([
      { element: b, delay: 0, duration: 450 },
      { element: y, delay: 150, duration: 300 }
    ]);
    e.first(),
      e.last("flipped"),
      e.invert(),
      e.play(),
      gc.addEventListenerOnce(y, "flipComplete", function() {
        v.addEventListener("click", a),
          m.addEventListener("click", u),
          y.addEventListener("click", u),
          gc.addKeyHandler(t, "Escape", 27, u),
          (h = "open");
      });
  }
  function u(e) {
    "undefined" == typeof e && (e = !1),
      "open" === h && ((h = "transition"), d(f, e));
  }
  function d(e, n) {
    function o() {
      y.classList.remove("flipped"), b.classList.remove("flipped");
    }
    function s() {
      v.removeEventListener("click", a),
        m.removeEventListener("click", u),
        gc.removeKeyHandler(t, "Escape"),
        e(n);
    }
    if (n === !0) o(), s();
    else {
      var c = FLIP.group([
        { element: y, delay: 0, duration: 150 },
        { element: b, delay: 100, duration: 200 }
      ]);
      c.first(),
        o(),
        c.last(),
        c.invert(),
        c.play(),
        gc.addEventListenerOnce(b, "flipComplete", s);
    }
  }
  function f(e) {
    function t() {
      m.classList.remove("flipped"), v.classList.remove("flipped"), c();
    }
    if (e === !0) {
      t();
      var o = 0;
    } else {
      var o = 450,
        a = FLIP.group([
          { element: m, easing: s, delay: 0, duration: o - 50 },
          { element: v, easing: s, delay: 0, duration: o - 150 }
        ]);
      a.first(), t(), a.last(), a.invert(), a.play();
    }
    setTimeout(function() {
      m.classList.remove("overlayed"),
        v.removeAttribute("style"),
        (h = "closed"),
        n.onClose();
    }, o);
  }
  var p = {
    top: "16px",
    color: function() {
      return window.getComputedStyle(e).backgroundColor;
    },
    onClose: function() {},
    mobileOnly: !1
  };
  if ("undefined" == typeof n) n = p;
  else for (var g in p) n.hasOwnProperty(g) || (n[g] = p[g]);
  var h = "closed",
    m = document.getElementById(t);
  if (!m) return void console.error("Unable to get modal #" + t);
  var v = m.children[0],
    y = v.getElementsByClassName("modal__close")[0],
    b = v.getElementsByClassName("modal__content")[0];
  if (n.mobileOnly === !0) {
    if (gc.width >= 1024)
      return void console.log("Modal not opened b/c mobileOnly = true");
    gc.addResizeCallback(t, function(e, o) {
      if (o >= 1024) {
        gc.removeResizeCallback(t),
          (m.style.display = "none !important;"),
          u(!0);
        var s = n.onClose;
        n.onClose = function() {
          s(), (m.style.display = "");
        };
      }
    });
  }
  return "closed" === h && ((h = "transition"), i(!0)), u;
}),
  (gc.setupGlobalSearch = function(e) {
    window.scrollTo(0, 0);
    var t = document.querySelector(".gs"),
      n = t.querySelector(".smart-search__input");
    t.classList.contains("gs--setup") ||
      (t.classList.add("gs--setup"), gc.initSmartSearch(t, n, !0, !0)),
      gc.iOS
        ? n.focus()
        : setTimeout(function() {
            n.focus();
          }, e);
  }),
  (gc.initSmartSearch = function(e, t, n, o) {
    function s() {
      (f = t.value),
        f && f !== d
          ? (d = f)
          : (gc.removeKeyHandler("clearSmartSearchInput", "Escape"),
            gc.fireNextKeyHandler("Escape") === !1 &&
              gc.addKeyHandler("clearSmartSearchInput", "Escape", 27, s)),
        (t.value = "");
    }
    function c(e) {
      return "user:" === e.substr(0, 5) && e.substr(5);
    }
    function a(e) {
      m ||
        ("string" == typeof e && e.length >= _
          ? (window.location.href = "/search/?q=" + encodeURIComponent(t.value))
          : ((t.value = ""),
            (t.placeholder = "Please enter at least " + _ + " letters."),
            t.focus()));
    }
    function i(e) {
      (v += e ? 1 : -1),
        1 === v && e
          ? (g.style.opacity = "1")
          : v || e || (g.style.opacity = "");
    }
    function r(e) {
      b.indexOf(e) === -1 &&
        (b.push(e),
        i(!0),
        gc
          .loadCachedJSON(
            "people-search-" + e,
            "https://services.goshen.edu/people/search/?person=" + e
          )
          .then(function(e) {
            u(l(e), "person"), i(!1);
          })
          ["catch"](function(e) {
            console.error("Unable to fetch GC people by trigram", e), i(!1);
          }));
    }
    function l(e) {
      var t,
        n,
        o,
        s = [];
      for (t = 0; t < e.facultyStaff.length; t++)
        (n = e.facultyStaff[t]),
          (o = n.username),
          y.indexOf(o) === -1 &&
            (y.push(o),
            s.push({
              title: n.name,
              desc: n.pos_desc,
              url: "user:" + n.username,
              person: JSON.stringify({
                email: n.email,
                ext: n.ext,
                phone: n.phone,
                location: n.office,
                title: n.pos_desc
              })
            }));
      if (e.students)
        for (t = 0; t < e.students.length; t++)
          (n = e.students[t]),
            (o = n.username),
            y.indexOf(o) === -1 &&
              (y.push(o),
              s.push({
                title: n.name,
                desc: "Student",
                url: "user:" + o,
                person: JSON.stringify({
                  email: o + "@goshen.edu",
                  location: n.location,
                  phone: n.phone,
                  title: "Student"
                })
              }));
      return s;
    }
    function u(e, n) {
      e.length &&
        ((e = e.map(function(e) {
          return (
            (e.type = n),
            (e.match = e.match || e.title || ""),
            e.match ||
              console.error('Search suggestion has no "match" or "title"', e),
            (e.match = e.match
              .toLowerCase()
              .replace(/[^a-z\d\s]/g, "")
              .replace(/\s\s+/g, " ")),
            e
          );
        })),
        (L = L.concat(e)),
        setTimeout(function() {
          (t.cache = {}), w();
        }, 100));
    }
    var d,
      f,
      p = document.getElementById("modal__content--contact"),
      g = e.querySelector(".smart-search__loading-icon"),
      h = e.querySelector(".smart-search__button"),
      m = !1,
      v = 0,
      y = [],
      b = [],
      _ = 3,
      L = [],
      w = function() {};
    "boolean" != typeof o && (o = !0),
      t.addEventListener("keypress", function(e) {
        13 === e.keyCode && a(t.value);
      }),
      t.addEventListener("focus", function() {
        gc.addKeyHandler("clearSmartSearchInput", "Escape", 27, s);
      }),
      t.addEventListener("blur", function() {
        gc.removeKeyHandler("clearSmartSearchInput", "Escape");
      }),
      h.addEventListener("click", function() {
        a(t.value);
      }),
      i(!0),
      gc
        .load("autocomplete", "~/js/dist/auto-complete.min.js")
        .then(function() {
          i(!1),
            new autoComplete({
              selector: t,
              minChars: _,
              menuClass: "smart-search__suggestions",
              source: function e(t, o) {
                var s = t;
                t = t.toLowerCase().replace(/[^a-z\d\s]/g, "");
                var c = RegExp("\\b" + t, "i"),
                  a = [];
                a.push({
                  title: s,
                  url: "/search/?q=" + encodeURIComponent(t),
                  default: !0,
                  type: "link"
                }),
                  (a = a.concat(
                    L.filter(function(e) {
                      return c.test(e.match);
                    })
                  )),
                  n && t.length >= 3 && r(t.substr(0, 3)),
                  (w = function() {
                    e(t, o);
                  }),
                  o(a, t);
              },
              renderItem: function(e, t) {
                if (e["default"])
                  o =
                    "<b>" +
                    e.title +
                    '</b><span class="smart-search__suggestion--default-search"> - Search goshen.edu</span>';
                else {
                  t = t.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");
                  var n = new RegExp(
                      "\\b(" + t.split(" ").join("|") + ")",
                      "i"
                    ),
                    o = e.title.replace(n, "<b>$1</b>");
                }
                var s = "smart-search__suggestion--" + e.type;
                e.desc && (s += " smart-search__suggestion--has-desc");
                var c = [
                  '<div class="smart-search__suggestion autocomplete-suggestion ',
                  s,
                  '" data-val="',
                  e.title,
                  '" data-url="',
                  e.url,
                  '"'
                ];
                switch (
                  ("person" === e.type &&
                    (c = c.concat([
                      "\" data-person='",
                      e.person.replace(/'/g, "&#39;"),
                      "'"
                    ])),
                  c.push(">"),
                  e.type)
                ) {
                  case "person":
                    c.push(
                      '<svg class="icon smart-search__suggestion-icon smart-search__suggestion-icon--person" viewBox="0 0 16 16"><path d="M9 11.041v-0.825c1.102-0.621 2-2.168 2-3.716 0-2.485 0-4.5-3-4.5s-3 2.015-3 4.5c0 1.548 0.898 3.095 2 3.716v0.825c-3.392 0.277-6 1.944-6 3.959h14c0-2.015-2.608-3.682-6-3.959z"></path></svg>'
                    );
                    break;
                  case "program":
                    c.push(
                      '<svg class="icon smart-search__suggestion-icon smart-search__suggestion-icon--cap" viewBox="0 0 41 32"><path d="M31.7 15l.3 5.6q0 1.2-1.5 2.3t-4.2 1.5-5.7.6-5.8-.5-4.2-1.6T9 20.5l.5-5.7L19.7 18h1.7zM41 9q0 .6-.2.7l-20 6.3h-.4L8.7 12.3q-.7.6-1.2 2T7 17.5q1 .6 1 2 0 1.2-1 1.8L8 29v.5l-.6.2H4q-.3 0-.4-.2l-.2-.4 1-7.7q-1-.6-1-2 0-1.2 1.2-2 .2-3.5 1.7-5.7l-6-2q-.3 0-.3-.5t.4-.4l20-6.3q0 0 .2 0l20 6.3q.5 0 .5.5z"/></svg>'
                    );
                  default:
                    c.push(
                      '<svg class="icon smart-search__suggestion-icon smart-search__suggestion-icon--link" viewBox="0 0 16 16"><path d="M4 10c0 0 0.919-3 6-3v3l6-4-6-4v3c-4 0-6 2.495-6 5zM11 12h-9v-6h1.967c0.158-0.186 0.327-0.365 0.508-0.534 0.687-0.644 1.509-1.135 2.439-1.466h-6.914v10h13v-4.197l-2 1.333v0.864z"></path></svg>'
                    );
                }
                return (
                  (c = c.concat([
                    o,
                    '<div class="smart-search__suggestion-desc">',
                    e.desc,
                    "</div>",
                    "</div>"
                  ])),
                  c.join("")
                );
              },
              onSelect: function(e, n, o) {
                (m = !0),
                  setTimeout(function() {
                    m = !1;
                  }, 100);
                var s = o.getAttribute("data-url"),
                  a = c(s);
                if (a !== !1) {
                  var i = o.getAttribute("data-val"),
                    r = JSON.parse(o.getAttribute("data-person")),
                    l = document.querySelector(".smart-search__suggestions");
                  l.classList.add("smart-search__suggestions--selected");
                  var u = new Image();
                  u.src =
                    "https://photo-dir.goshen.edu/showPic.php?uid=" +
                    a +
                    "&size=large";
                  var d = [r.ext, r.phone]
                      .filter(function(e) {
                        return void 0 != e;
                      })
                      .join(","),
                    f = {
                      name: i,
                      username: a,
                      email: r.email,
                      phone: d,
                      title: r.title,
                      location: r.location || "",
                      additionalclasses: "contact--gs-modal"
                    };
                  "Student" !== r.title && (f.lookuplinknow = "true");
                  var g = "[gc_contact_box ";
                  for (var h in f) g += h + '="' + f[h] + '" ';
                  (g += "]"),
                    (g = encodeURIComponent(g)),
                    gc
                      .loadJSON(
                        "/faculty/wp-json/shared-gc/v1/shortcode/?shortcode=" +
                          g
                      )
                      .then(function(e) {
                        e.hasOwnProperty("html")
                          ? (p.innerHTML = e.html)
                          : console.error(e);
                      }),
                    gc.openModal(o, "modal--contact", {
                      top: "48px",
                      onClose: function() {
                        l.classList.remove(
                          "smart-search__suggestions--selected"
                        ),
                          t.removeAttribute("readonly"),
                          t.focus(),
                          (p.innerHTML = "");
                      }
                    }),
                    setTimeout(function() {
                      t.setAttribute("readonly", !0), t.blur();
                    }, 0);
                } else window.location.href = o.getAttribute("data-url");
              }
            }),
            o &&
              (t.focus(), (t.selectionStart = t.selectionEnd = t.value.length)),
            t.addEventListener("focus", function() {
              t.value.length >= _ && t.updateSC(0);
            });
        })
        ["catch"](function() {
          i(!1);
        }),
      !(function() {
        var e = "programs-of-study-search";
        i(!0),
          gc
            .loadCachedJSON(e, "~/js/json/" + e + ".json")
            .then(function(e) {
              u(e), i(!1);
            })
            ["catch"](function() {
              i(!1);
            });
      })(),
      !(function() {
        var e = "global-search-suggestions";
        i(!0),
          gc
            .loadCachedJSON(
              e,
              "/wp-content/shared-gc/includes/configs/" + e + ".json"
            )
            .then(function(e) {
              u(e), i(!1);
            })
            ["catch"](function() {
              i(!1);
            });
      })();
  }),
  !(function() {
    function e(e, t) {
      window.scrollTo(0, 0),
        n(!0, e, t),
        e.dropdown.classList.add("global-dropdown--opened");
    }
    function t(e, t) {
      n(!1, e, t), e.dropdown.classList.remove("global-dropdown--opened");
    }
    function n(e, t, n) {
      requestAnimationFrame(function() {
        e
          ? (t.button.classList.add(t.buttonClass),
            (n.button.style.opacity = "0"))
          : (t.button.classList.remove(t.buttonClass),
            (n.button.style.opacity = ""));
      });
    }
    function o(e) {
      var t = d[e],
        n = "nav" === e ? d.search : d.nav;
      return t.isOpen
        ? void console.warn("Attempted to open the same thing twice:", e)
        : ("search" === e &&
            gc.width >= 1024 &&
            n.isOpen &&
            (n.closeMobile(n, t), (n.isOpen = !1)),
          gc.width < 1024 ? t.openMobile(t, n) : t.openDesktop(t, n),
          t.open(),
          void (t.isOpen = !t.isOpen));
    }
    function s(e) {
      var t = d[e],
        n = "nav" === e ? d.search : d.nav;
      return t.isOpen
        ? (gc.width < 1024 ? t.closeMobile(t, n) : t.closeDesktop(t, n),
          t.close(),
          void (t.isOpen = !t.isOpen))
        : void console.warn("Attempted to close the same thing twice:", e);
    }
    function c() {
      s("search");
    }
    function a() {
      o("search");
    }
    function i(e) {
      var t = e.dropdown,
        n = "global-dropdown--opened",
        o = "gs--faded-out";
      t.classList.add(o),
        requestAnimationFrame(function() {
          t.classList.add(n),
            requestAnimationFrame(function() {
              t.classList.remove(o), gc.setupGlobalSearch(700);
            });
        });
    }
    function r(e) {
      var t = e.dropdown,
        n = "global-dropdown--opened",
        o = "gs--faded-out";
      requestAnimationFrame(function() {
        t.classList.add(o);
      }),
        setTimeout(function() {
          t.classList.remove(n), t.classList.remove(o);
        }, 700);
    }
    function l(e) {
      var t = d[e];
      t.button.addEventListener("click", function() {
        t.isOpen ? s(e) : o(e);
      }),
        t.dropdown.nextElementSibling.addEventListener("click", function() {
          s(e);
        }),
        "search" === e &&
          (document
            .getElementById("gn__label--search")
            .addEventListener("click", function() {
              o(e);
            }),
          gc.addKeyHandler("openGlobalSearch", "/", 191, function() {
            t.isOpen ? c() : a();
          }));
    }
    var u = gc.once(function() {
        var e = [];
        lscache.flushExpired();
        var t = lscache.get("globalNavCheckedInputIds");
        null !== t && (e = t);
        for (var n, o = 0; o < e.length; o++)
          (n = document.getElementById(e[o])), n && (n.checked = !0);
        gc.scripts.jquery.then(function() {
          $("#gn").on("change", ".gn__input", function() {
            if (this.checked === !0) e.push(this.id);
            else {
              var t = e.indexOf(this.id);
              t !== -1 && e.splice(t, 1);
            }
            lscache.set("globalNavCheckedInputIds", e, 129600);
          });
        });
      }),
      d = {
        nav: {
          isOpen: !1,
          button: document.getElementById("gnbar__toggle--nav"),
          buttonClass: "animated-hamburger--animate-to-x",
          dropdown: document.getElementById("gn"),
          openMobile: function(t, n) {
            e(t, n), u();
          },
          openDesktop: function() {},
          open: function() {},
          closeMobile: t,
          closeDesktop: function() {},
          close: function() {}
        },
        search: {
          isOpen: !1,
          button: document.getElementById("gnbar__toggle--search"),
          buttonClass: "animated-search--animate-to-x",
          dropdown: document.querySelector(".gs"),
          openMobile: function(t, n) {
            e(t, n), gc.setupGlobalSearch(400);
          },
          openDesktop: function(e, t) {
            n(!0, e, t), i(e);
          },
          open: function() {
            gc.addKeyHandler("closeSearch", "Escape", 27, c);
          },
          closeMobile: t,
          closeDesktop: function(e, t) {
            n(!1, e, t), r(e);
          },
          close: function() {
            gc.removeKeyHandler("closeSearch", "Escape");
          }
        }
      };
    l("nav"), l("search");
  })(),
  !(function() {
    function e() {
      t(gc.width),
        m.addEventListener("click", n),
        v.addEventListener("click", o),
        g.addEventListener(
          "scroll",
          gc.debounce(function() {
            c(_.scrollLeft);
          }, 100)
        ),
        h.addEventListener(
          "scroll",
          gc.debounce(function() {
            c(_.scrollLeft);
          }, 100)
        ),
        gc.addResizeCallback("siteNavWrapper", function(e, n) {
          t(n);
        });
    }
    function t(e) {
      e < 480
        ? ((_ = g), (m.style.left = 0), (L = _.scrollWidth - e), (w = 250))
        : ((_ = h),
          (m.style.left = b.clientWidth + "px"),
          (L = _.scrollWidth - _.clientWidth),
          (w = 300)),
        L <= 0
          ? p.classList.remove("sn-bar--scrollable")
          : (p.classList.add("sn-bar--scrollable"), (L += y)),
        c(_.scrollLeft);
    }
    function n() {
      s(-w);
    }
    function o() {
      s(w);
    }
    function s(e) {
      var t = _.scrollLeft,
        n = t + e;
      r(t, n, 1e3, l);
    }
    function c(e) {
      L <= 0
        ? (i(m), i(v))
        : e <= 10
          ? (i(m), a(v))
          : e >= L - y
            ? (i(v), a(m))
            : (a(m), a(v));
    }
    function a(e) {
      e.classList.remove("hide"),
        setTimeout(function() {
          e.classList.add("sn-bar__scroll--visible");
        }, 100);
    }
    function i(e) {
      e.classList.remove("sn-bar__scroll--visible"),
        setTimeout(function() {
          e.classList.add("hide");
        }, 300);
    }
    function r(e, t, n, o) {
      var s = t - e,
        c = performance.now(),
        a = function(i) {
          var r = i ? i - c : 0,
            l = o(null, r, 0, 1, n);
          (_.scrollLeft = e + s * l),
            r < n && _.scrollLeft != t && requestAnimationFrame(a);
        };
      a();
    }
    function l(e, t, n, o, s) {
      return (t /= s / 2) < 1
        ? (o / 2) * t * t + n
        : (-o / 2) * (--t * (t - 2) - 1) + n;
    }
    function u() {
      requestAnimationFrame(function() {
        p.classList.add("sn-bar--hovered"), f(d, !0);
      }),
        f(u, !1);
    }
    function d(e) {
      (e.target !== g && e.target !== h) ||
        (p.classList.remove("sn-bar--hovered"), f(d, !1), f(u, !0));
    }
    function f(e, t) {
      t
        ? (g.addEventListener("touchstart", e, !!E && { passive: !0 }),
          g.addEventListener("mouseover", e))
        : (g.removeEventListener("touchstart", e, !!E && { passive: !0 }),
          g.removeEventListener("mouseover", e));
    }
    var p = document.getElementById("sn-bar"),
      g = document.getElementById("sn-bar__outer-wrap"),
      h = document.getElementById("sn-bar__inner-wrap");
    if (null !== g) {
      var m = document.getElementById("sn-bar__scroll--left"),
        v = document.getElementById("sn-bar__scroll--right"),
        y = 28,
        b = document.getElementById("sn-bar__heading");
      gc.load(
        "fontfaceobserver",
        "~/bower_components/fontfaceobserver/fontfaceobserver.standalone.js"
      )
        .then(function() {
          var t = new FontFaceObserver("Source Sans Pro");
          t.load()
            .then(function() {
              setTimeout(e, 200);
            })
            ["catch"](function() {
              setTimeout(e, 200);
            });
        })
        ["catch"](function() {
          setTimeout(e, 3e3);
        });
      var _,
        L,
        w,
        E = !1;
      try {
        var k = Object.defineProperty({}, "passive", {
          get: function() {
            E = !0;
          }
        });
        window.addEventListener("test", null, k);
      } catch (S) {}
      f(u, !0);
    }
  })(),
  !(function() {
    document.getElementsByClassName("sn-bar--hide-on-desktop").length ||
      gc.scripts.jquery.then(function() {
        gc.load(
          "sticky-kit",
          "~/bower_components/sticky-kit/jquery.sticky-kit.min.js"
        ).then(function() {
          function e() {
            n.stick_in_parent({
              parent: document.body,
              sticky_class: "sn-bar--sticky"
            });
          }
          function t() {
            n.trigger("sticky_kit:detach");
          }
          var n = $("#sn-bar");
          gc.width >= 1024 && e(),
            gc.addResizeCallback("snBarSticky", function(n, o) {
              n < 1024 && o >= 1024 ? e() : n >= 1024 && o < 1024 && t();
            });
        });
      });
  })(),
  !(function() {
    function e(e) {
      gc.scripts.jquery.then(function() {
        var t = "",
          n = 0;
        $(e.selector).on("focusin", function() {
          (n = window.performance.now()),
            this.id !== t && ((t = this.id), e.onFocusIn(this));
        }),
          $(e.selector).on("focusout", function() {
            var o = this,
              s = 25;
            setTimeout(function() {
              o.id !== t
                ? e.onFocusOut(o)
                : window.performance.now() - n > 2 * s &&
                  ((t = ""), e.onFocusOut(o), e.onAllFocusOut());
            }, s);
          });
      });
    }
    var t = document.getElementById("sn-bar"),
      n = {
        selector: ".sn-bar__item",
        onFocusIn: function(e) {
          e.classList.add("sn-bar__item--has-focus"),
            t.classList.add("sn-bar--focused");
        },
        onFocusOut: function(e) {
          e.classList.remove("sn-bar__item--has-focus");
        },
        onAllFocusOut: function() {
          t.classList.remove("sn-bar--focused");
        }
      };
    e(n);
    var o = {
      selector: ".gn__section",
      onFocusIn: function(e) {
        e.classList.add("gn__section--has-focus");
      },
      onFocusOut: function(e) {
        e.classList.remove("gn__section--has-focus");
      },
      onAllFocusOut: function() {}
    };
    gc.width >= 1024
      ? e(o)
      : gc.addResizeCallback("accessibleDropdowns", function(t, n) {
          n > 1024 && (e(o), gc.removeResizeCallback("accessibleDropdowns"));
        });
  })(),
  !(function() {
    function e() {
      for (
        var e = document.querySelectorAll(".gn__image img"), t = e.length - 1;
        t >= 0;
        t--
      )
        e[t].src = e[t].getAttribute("data-src");
    }
    var t = "loadGlobalNavImages",
      n = 1024;
    gc.width >= n
      ? e()
      : gc.addResizeCallback(t, function(o, s) {
          s >= n && (e(), gc.removeResizeCallback(t));
        });
  })(),
  !(function(e) {
    function t() {
      e.removeEventListener("scroll", t), n();
    }
    function n() {
      var t, n;
      (t = e.location.hash),
        t.length < 2 ||
          ((n = document.getElementById(t.slice(1))),
          null !== n &&
            n.getBoundingClientRect().top < 2 &&
            e.scrollBy(0, -o()));
    }
    function o() {
      return gc.width < 525 ? 120 : gc.width < 1024 ? 88 : 40;
    }
    (gc.fixedHeaderHeight = o),
      e.addEventListener("scroll", t),
      "onhashchange" in e && e.addEventListener("hashchange", n);
  })(window);
//# sourceMappingURL=../sourcemaps/header.min.js.map
