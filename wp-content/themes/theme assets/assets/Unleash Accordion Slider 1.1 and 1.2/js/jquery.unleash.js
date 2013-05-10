/**
 * jQuery Unleash v1.0.0
 *
 * Accordion jQuery image slider
 *
 * Created by Ali Alaa 2011-2012
 *
 * http://themeforest.net/user/alialaa
 *
 */ (function ($) {
    $.fn.unleash = function (options) {
        //plugin name - unleash
        var o = $.extend({}, $.fn.unleash.defaults, options);
        //Settings list and the default values
        $.fn.unleash.defaults = {
            duration: 700,
            childClassName: '.box',
            captionClassName: '.caption_1',
            captionMargin: '20px',
            SliderWidth: '960px',
            SliderHeight: '300px',
            width: 600,
            Event: "hover",
            easing: "quadEaseOut",
            captionEasing: "backEaseInOut",
            CollapseOnMouseLeave: true,
            CaptionAnimation: "opacity"
        };
        return this.each(function () {
            var obj = $(this);
            var temp = o.width;
            if (o.SliderWidth.replace("px", "") > $(window).width()) {
                o.width = temp * (($(window).width() / o.SliderWidth.replace("px", "")));
                obj.css("width", o.SliderWidth.replace("px", "") * ($(window).width() / o.SliderWidth.replace("px", "")));
                obj.find(o.childClassName).each(function (i) {
                    $(this).css({
                        left: (i * (obj.width()) / ((obj.find(o.childClassName).size()))) + 'px'
                    });
					obj.find(o.captionClassName).width(o.width - 2 * $(o.captionClassName).css('paddingRight').replace("px", "") - 2 * $(o.captionClassName).css('marginRight').replace("px", "") - $(o.captionClassName).css("border-left-width").replace("px", "") - $(o.captionClassName).css("border-right-width").replace("px", ""));
                });
            } else {
                o.width = temp;
                obj.css("width", o.SliderWidth);
				obj.find(o.captionClassName).width(o.width - 2 * $(o.captionClassName).css('paddingRight').replace("px", "") - 2 * $(o.captionClassName).css('marginRight').replace("px", "") - $(o.captionClassName).css("border-left-width").replace("px", "") - $(o.captionClassName).css("border-right-width").replace("px", ""));
            }
            if (o.SliderHeight.replace("px", "") > $(window).height()) {
                obj.css("height", o.SliderHeight.replace("px", "") * ($(window).height() / o.SliderHeight.replace("px", "")));
                obj.find(o.childClassName).css("height", o.SliderHeight.replace("px", "") * ($(window).height() / o.SliderHeight.replace("px", "")));
            } else {
                obj.css("height", o.SliderHeight);
                obj.find(o.childClassName).css("height", o.SliderHeight);
            }
            var y0 = (obj.width()) / ((obj.find(o.childClassName).size()));
            obj.find(o.childClassName).each(function (i) {
                $(this).css({
                    left: (i * y0) + 'px'
                });
            });
            
            $(window).resize(function () {
				 collapse();
                if (o.SliderWidth.replace("px", "") > $(window).width()) {
                    o.width = temp * (($(window).width() / o.SliderWidth.replace("px", "")));
                    obj.css("width", o.SliderWidth.replace("px", "") * ($(window).width() / o.SliderWidth.replace("px", "")));
                    obj.find(o.childClassName).each(function (i) {
						
                        $(this).animate({
                            left: (i * (obj.width()) / ((obj.find(o.childClassName).size()))) + 'px'
                        }, {
                            queue: false,
                            duration: o.duration,
                            easing: o.easing
                        });
                    });
					obj.find(o.captionClassName).width(o.width - 2 * $(o.captionClassName).css('paddingRight').replace("px", "") - 2 * $(o.captionClassName).css('marginRight').replace("px", "") - $(o.captionClassName).css("border-left-width").replace("px", "") - $(o.captionClassName).css("border-right-width").replace("px", ""));
                } else {
                    o.width = temp;
                    obj.css("width", o.SliderWidth);
                    obj.find(o.childClassName).each(function (i) {
                        $(this).animate({
                            left: (i * (o.SliderWidth.replace("px", "")) / ((obj.find(o.childClassName).size()))) + 'px'
                        }, {
                            queue: false,
                            duration: o.duration,
                            easing: o.easing
                        });
                    });
					obj.find(o.captionClassName).width(o.width - 2 * $(o.captionClassName).css('paddingRight').replace("px", "") - 2 * $(o.captionClassName).css('marginRight').replace("px", "") - $(o.captionClassName).css("border-left-width").replace("px", "") - $(o.captionClassName).css("border-right-width").replace("px", ""));
                }
                if (o.SliderHeight.replace("px", "") > $(window).height()) {
                    obj.animate({
                        height: o.SliderHeight.replace("px", "") * ($(window).height() / o.SliderHeight.replace("px", ""))
                    }, {
                        queue: false,
                        duration: o.duration,
                        easing: o.easing
                    });
                    obj.find(o.childClassName).animate({
                        height: o.SliderHeight.replace("px", "") * ($(window).height() / o.SliderHeight.replace("px", ""))
                    }, {
                        queue: false,
                        duration: o.duration,
                        easing: o.easing
                    });
                } else {
                    obj.animate({
                        height: o.SliderHeight
                    }, {
                        queue: false,
                        duration: o.duration,
                        easing: o.easing
                    });
                    obj.find(o.childClassName).animate({
                        height: o.SliderHeight
                    }, {
                        queue: false,
                        duration: o.duration,
                        easing: o.easing
                    });
                }
            });
			
            switch (o.CaptionAnimation) {
            case "pop-up":
                obj.find(o.captionClassName).each(function (index) {
                    var $this = $(this);
                    $this.css({
                        bottom: -$(this).outerHeight(true) - 5
                    });
                });
                break;
            case "opacity":
                obj.find(o.captionClassName).each(function (index) {
                    var $this = $(this);
                    $this.css({
                        bottom: 0
                    });
                    $this.css({
                        opacity: 0
                    });
                });
                break;
            case "rotate":
                obj.find(o.captionClassName).each(function (index) {
                    var $this = $(this);
                    $this.css({
                        marginBottom: "0px"
                    });
                    $this.width(o.SliderHeight.replace("px", "") - 2 * $this.css('paddingRight').replace("px", "") - 2 * $this.css('marginRight').replace("px", ""));
                    $this.css({
                        bottom: -((0.5 * $(this).outerHeight(true)) + (0.5 * $(this).outerWidth(true)))
                    });
                    $this.rotate('-90deg');
                });
                break;
            }
            if (o.Event == "hover") {
                obj.find(o.childClassName).hover(function () {
                    var hov = $(this);
                    var y = (((obj.width()) - o.width)) / ((obj.find(o.childClassName).size() - 1));
                    if ((hov.width() == obj.find(o.childClassName).width()) || (hov.width() == y)) {
                        obj.find(o.childClassName).removeClass('featured');
                        hov.addClass('featured');
                        var ind = obj.find(o.childClassName).index(this);
                        obj.children('div').each(function (i) {
                            var $this = $(this);
                            if (i == 0) {
                                $this.stop().animate({
                                    left: '0px'
                                }, {
                                    queue: false,
                                    duration: o.duration,
                                    easing: o.easing
                                });
                            } else {
                                if (i == ind) {
                                    $this.stop().animate({
                                        left: (i) * y + 'px'
                                    }, {
                                        queue: false,
                                        duration: o.duration,
                                        easing: o.easing
                                    });
                                } else {
                                    if (i < ind) {
                                        $this.stop().animate({
                                            left: (i) * y + 'px'
                                        }, {
                                            queue: false,
                                            duration: o.duration,
                                            easing: o.easing
                                        });
                                    } else {
                                        if (i > ind) {
                                            $this.stop().animate({
                                                left: o.width + (i - 1) * y + 'px'
                                            }, {
                                                queue: false,
                                                duration: o.duration,
                                                easing: o.easing
                                            });
                                        }
                                    }
                                }
                            }
                        });
                        switch (o.CaptionAnimation) {
                        case "pop-up":
                            obj.find(o.captionClassName).each(function (index) {
                                var $this = $(this);
                                $this.animate({
                                    bottom: -$this.outerHeight(true) - 5
                                }, {
                                    queue: false,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
                            });
                            obj.find('.featured').find(o.captionClassName).animate({
                                bottom: '0px'
                            }, {
                                queue: false,
                                duration: o.duration,
                                easing: o.captionEasing
                            });
                            break;
                        case "opacity":
                            obj.find(o.captionClassName).stop().animate({
                                opacity: 0
                            }, {
                                queue: false,
                                duration: o.duration,
                                easing: o.easing
                            });
                            obj.find('.featured').find(o.captionClassName).stop().animate({
                                opacity: 1
                            }, {
                                queue: false,
                                duration: o.duration,
                                easing: o.easing
                            });
                            break;
                        case "rotate":
                            obj.find(o.captionClassName).each(function (index) {
                                var $this = $(this)
                                $this.stop().animate({
                                    rotate: -90
                                }, {
                                    queue: true,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
                                $this.stop().animate({
                                    bottom: -((0.5 * $this.outerHeight(true)) + (0.5 * $this.outerWidth(true)))
                                }, {
                                    queue: true,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
                            });
                            obj.find('.featured').find(o.captionClassName).stop().animate({
                                bottom: 0.1 * $(this).outerHeight(true)
                            }, {
                                queue: true,
                                duration: o.duration,
                                easing: o.captionEasing
                            });
                            obj.find('.featured').find(o.captionClassName).animate({
                                rotate: 0
                            }, {
                                queue: true,
                                duration: o.duration,
                                easing: o.captionEasing
                            })
                            break;
                        }
                    }
                })
            } else {
                if (o.Event == "click") {
                    obj.find(o.childClassName).click(function () {
                        var clk = $(this)
                        var y = (((obj.width()) - o.width)) / ((obj.find(o.childClassName).size() - 1));
                        if ((clk.width() == $(o.childClassName).width()) || (clk.width() == y)) {
                            obj.find(o.childClassName).removeClass('featured');
                            clk.addClass('featured');
                            var ind = obj.find(o.childClassName).index(this);
                            obj.children('div').each(function (i) {
                                var $this = $(this);
                                if (i == 0) {
                                    $this.animate({
                                        left: '0px'
                                    }, {
                                        queue: false,
                                        duration: o.duration,
                                        easing: o.easing
                                    });
                                } else {
                                    if (i == ind) {
                                        $this.animate({
                                            left: (i) * y + 'px'
                                        }, {
                                            queue: false,
                                            duration: o.duration,
                                            easing: o.easing
                                        });
                                    } else {
                                        if (i < ind) {
                                            $this.animate({
                                                left: (i) * y + 'px'
                                            }, {
                                                queue: false,
                                                duration: o.duration,
                                                easing: o.easing
                                            });
                                        } else {
                                            if (i > ind) {
                                                $this.animate({
                                                    left: o.width + (i - 1) * y + 'px'
                                                }, {
                                                    queue: false,
                                                    duration: o.duration,
                                                    easing: o.easing
                                                });
                                            }
                                        }
                                    }
                                }
                            });
                            switch (o.CaptionAnimation) {
                            case "pop-up":
                                obj.find(o.captionClassName).each(function (index) {
                                    var $this = $(this);
                                    $this.animate({
                                        bottom: -$this.outerHeight(true) - 5
                                    }, {
                                        queue: false,
                                        duration: o.duration,
                                        easing: o.captionEasing
                                    });;
                                });
                                obj.find('.featured').find(o.captionClassName).animate({
                                    bottom: '0px'
                                }, {
                                    queue: false,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
                                break;
                            case "opacity":
                                obj.find(o.captionClassName).animate({
                                    opacity: 0
                                }, {
                                    queue: false,
                                    duration: o.duration,
                                    easing: o.easing
                                });
                                obj.find('.featured').find(o.captionClassName).animate({
                                    opacity: 1
                                }, {
                                    queue: false,
                                    duration: o.duration,
                                    easing: o.easing
                                });
                                break;
                            case "rotate":
                                obj.find(o.captionClassName).each(function (index) {
                                    var $this = $(this)
                                    $this.animate({
                                        rotate: -90
                                    }, {
                                        queue: true,
                                        duration: o.duration,
                                        easing: o.captionEasing
                                    });
                                    $this.animate({
                                        bottom: -((0.5 * $this.outerHeight(true)) + (0.5 * $this.outerWidth(true)))
                                    }, {
                                        queue: true,
                                        duration: o.duration,
                                        easing: o.captionEasing
                                    });
                                });
                                obj.find('.featured').find(o.captionClassName).stop().animate({
                                    bottom: 0.1 * $(this).outerHeight(true)
                                }, {
                                    queue: true,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
                                obj.find('.featured').find(o.captionClassName).stop().animate({
                                    rotate: 0
                                }, {
                                    queue: true,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                })
                                break;
                            }
                        }
                    })
                }
            }
			function collapse() {
				
                    obj.find(o.childClassName).each(function (i) {
                        var $this = $(this);
                        $this.stop().animate({
                            left: (i * (obj.width()) / ((obj.find(o.childClassName).size()))) + 'px'
                        }, {
                            queue: false,
                            duration: o.duration,
                            easing: o.easing
                        });
                    });
                    switch (o.CaptionAnimation) {
                    case "pop-up":
                        obj.find(o.captionClassName).each(function (index) {
                            var $this = $(this);
                            $this.animate({
                                bottom: -$(this).outerHeight(true) - 5
                            }, {
                                queue: false,
                                duration: o.duration,
                                easing: o.captionEasing
                            });;
                        });
                        break;
                    case "opacity":
                        obj.find(o.captionClassName).stop().animate({
                            opacity: 0
                        }, {
                            queue: false,
                            duration: o.duration,
                            easing: o.easing
                        });
                        break;
                    case "rotate":
                        obj.find(o.captionClassName).each(function (index) {
                            var $this = $(this);
                            if (o.Event == "click") {
                                $this.animate({
                                    rotate: -90
                                }, {
                                    queue: true,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
                                $this.animate({
                                    bottom: -((0.5 * $(this).outerHeight(true)) + (0.5 * $(this).outerWidth(true)))
                                }, {
                                    queue: true,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
                            } else {
                                if (o.Event == "hover") {
                                    $this.stop().animate({
                                        rotate: -90
                                    }, {
                                        queue: true,
                                        duration: o.duration,
                                        easing: o.captionEasing
                                    });
                                    $this.stop().animate({
                                        bottom: -((0.5 * $(this).outerHeight(true)) + (0.5 * $(this).outerWidth(true)))
                                    }, {
                                        queue: true,
                                        duration: o.duration,
                                        easing: o.captionEasing
                                    });
                                }
                            }
                        });
                        break;
                    }
			};
            if (o.CollapseOnMouseLeave) {
                obj.mouseleave(function () {
					 collapse();
                });
            }
			
        });
    }
})(jQuery);