/**
* jQuery Unleash v1.2.0
*
* Accordion jQuery image slider
*
* Created by Ali Alaa 2011-2012
*
* http://themeforest.net/user/alialaa
*
*/
(function ($) {
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
			OpenFirstOnload: true,
            easing: "quadEaseOut",
            captionEasing: "backEaseInOut",
            CollapseOnMouseLeave: true,
            CaptionAnimation: "opacity"
        };
        return this.each(function () {
            
            var obj = $(this);
			var wh_ratio = o.SliderHeight.replace("px","")/o.SliderWidth.replace("px","");
			var slider_width  = obj.closest('div').width();
			o.SliderWidth = slider_width;
            obj.height(slider_width*(wh_ratio));
            obj.find(o.childClassName).height(slider_width*(wh_ratio));
			obj.find(o.childClassName).find('img').height(slider_width*(wh_ratio));
            var y0 = (slider_width) / ((obj.find(o.childClassName).size()));
            obj.find(o.childClassName).each(function (i) {
                $(this).css({
                    left: (i * y0) + 'px'
                });
            });
			
			if(o.OpenFirstOnload){
			 obj.find(o.childClassName).eq(0).addClass('featured');
			 var y1 = (((obj.width()) - (obj.width()*o.width))) / ((obj.find(o.childClassName).size() - 1));
			obj.find(o.childClassName).each(function (i) {
				if(i >= 1){
                $(this).animate({left: obj.width()*o.width+((i-1) * y1) + 'px'},{duration:o.duration, easing: o.easing} );
				}
				  switch (o.CaptionAnimation) {
                            case "pop-up":
                           
                            obj.find('.featured').find(o.captionClassName).animate({
                                bottom: '0px'
                                }, {
                                queue: false,
                                duration: o.duration,
                                easing: o.captionEasing
                            });
                            break;
                            case "opacity":
                            
                            obj.find('.featured').find(o.captionClassName).stop().animate({
                                opacity: 1
                                }, {
                                queue: false,
                                duration: o.duration,
                                easing: o.easing
                            });
                            break;
                            case "rotate":
                           
                            obj.find('.featured').find(o.captionClassName).animate({
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
							var space =  2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") + 2 * obj.find(o.captionClassName).css('marginRight').replace("px", "");
							obj.find('.featured').find(o.captionClassName).stop().animate({
                                width: obj.width()*o.width-space +"px"
                                }, {
                                queue: true,
                                duration: o.duration,
                                easing: o.captionEasing
                            })
                            break;
                        }
            });
		}
			
			$(window).resize(function () {
				collapse();
				
				var slider_width  = obj.closest('div').width();
			o.SliderWidth = slider_width;
            obj.height(slider_width*(wh_ratio));
            obj.find(o.childClassName).height(slider_width*(wh_ratio));
			obj.find(o.childClassName).find('img').height(slider_width*(wh_ratio));
            var y0 = (slider_width) / ((obj.find(o.childClassName).size()));
            obj.find(o.childClassName).each(function (i) {
                $(this).css({
                    left: (i * y0) + 'px'
                });
				
            });
			obj.find(o.captionClassName).width(slider_width*o.width - 2 * $(o.captionClassName).css('paddingRight').replace("px", "") - 2 * $(o.captionClassName).css('marginRight').replace("px", "") - $(o.captionClassName).css("border-left-width").replace("px", "") - $(o.captionClassName).css("border-right-width").replace("px", ""));
			
			obj.find(o.captionClassName).height(0.2*(obj.height() - 2 * $(o.captionClassName).css('paddingTop').replace("px", "") - 2 * $(o.captionClassName).css('marginBottom').replace("px", "") - $(o.captionClassName).css("border-top-width").replace("px", "") - $(o.captionClassName).css("border-bottom-width").replace("px", "")));
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
                    obj.find(o.captionClassName).width(obj.height() - 2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") - 2 * obj.find(o.captionClassName).css('marginRight').replace("px", ""));
                    $this.css({
                        bottom: -((0.5 * $(this).outerHeight(true)) + (0.5 * $(this).outerWidth(true)))
                    });
                    $this.rotate('-90deg');
                });
                break;
            }
			});
			
			
           
            obj.find(o.captionClassName).width(slider_width*o.width - 2 * $(o.captionClassName).css('paddingRight').replace("px", "") - 2 * $(o.captionClassName).css('marginRight').replace("px", "") - $(o.captionClassName).css("border-left-width").replace("px", "") - $(o.captionClassName).css("border-right-width").replace("px", ""));
			
			obj.find(o.captionClassName).height(0.2*(obj.height() - 2 * $(o.captionClassName).css('paddingTop').replace("px", "") - 2 * $(o.captionClassName).css('marginBottom').replace("px", "") - $(o.captionClassName).css("border-top-width").replace("px", "") - $(o.captionClassName).css("border-bottom-width").replace("px", "")));
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
                    obj.find(o.captionClassName).width(obj.height() - 2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") - 2 * obj.find(o.captionClassName).css('marginRight').replace("px", ""));
                    $this.css({
                        bottom: -((0.5 * $(this).outerHeight(true)) + (0.5 * $(this).outerWidth(true)))
                    });
                    $this.rotate('-90deg');
                });
                break;
            }
			
			function hover_func() {
				 obj.find(o.childClassName).hover(function () {
                    var hov = $(this);
                    var y = (((obj.width()) - obj.width()*o.width)) / ((obj.find(o.childClassName).size() - 1));
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
                                                left: obj.width()*o.width + (i - 1) * y + 'px'
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
								
									var space2 =  obj.height() - 2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") - 2 * obj.find(o.captionClassName).css('marginRight').replace("px", "");
									
							$this.stop().animate({
                                width: space2 +"px"
                                }, {
							    queue: true,
                                duration: o.duration,
                                easing: o.captionEasing
                            })
							$this.stop().animate({
                                    rotate: -90
                                    }, {    
									queue: true,                            
                                    duration: o.duration,
                                    easing: o.captionEasing
                                })
								$this.stop().animate({
                                    bottom: -((0.5 * $this.outerHeight(true)) + (0.5 * $this.outerWidth(true)))
                                    }, {
										queue: true,
                                    duration: o.duration,
                                    easing: o.captionEasing
                                });
								$this.css('overflow','auto');
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
							var space =  2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") + 2 * obj.find(o.captionClassName).css('marginRight').replace("px", "");
							obj.find('.featured').find(o.captionClassName).animate({
                                width: obj.width()*o.width-space +"px"
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
			
			function click_func() {
				obj.find(o.childClassName).click(function () {
                        var clk = $(this)
                        var y = (((obj.width()) - obj.width()*o.width)) / ((obj.find(o.childClassName).size() - 1));
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
                                        queue: true,
                                        duration: o.duration,
                                        easing: o.easing
                                    });
                                    } else {
                                    if (i == ind) {
                                        $this.animate({
                                            left: (i) * y + 'px'
                                            }, {
                                            queue: true,
                                            duration: o.duration,
                                            easing: o.easing
                                        });
                                        } else {
                                        if (i < ind) {
                                            $this.animate({
                                                left: (i) * y + 'px'
                                                }, {
                                                queue: true,
                                                duration: o.duration,
                                                easing: o.easing
                                            });
                                            } else {
                                            if (i > ind) {
                                                $this.animate({
                                                    left: obj.width()*o.width + (i - 1) * y + 'px'
                                                    }, {
                                                    queue: true,
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
								
									var space2 =  obj.height() - 2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") - 2 * obj.find(o.captionClassName).css('marginRight').replace("px", "");
									
							$this.stop().animate({
                                width: space2 +"px"
                                }, {
							    queue: true,
                                duration: o.duration,
                                easing: o.captionEasing
                            })
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
									$this.css('overflow','auto');
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
								var space =  2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") + 2 * obj.find(o.captionClassName).css('marginRight').replace("px", "");
							obj.find('.featured').find(o.captionClassName).stop().animate({
                                width: obj.width()*o.width-space +"px"
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
		
            if (o.Event == "hover") {
               hover_func()
                } else {
                if (o.Event == "click") {
                    click_func()
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
								var space2 =  obj.height() - 2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") - 2 * obj.find(o.captionClassName).css('marginRight').replace("px", "");
							$this.stop().animate({
                                width: space2 +"px"
                                }, {
                                queue: true,
                                duration: o.duration,
                                easing: o.captionEasing
                            })
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
									
									var space2 =  obj.height() - 2 * obj.find(o.captionClassName).css('paddingRight').replace("px", "") - 2 * obj.find(o.captionClassName).css('marginRight').replace("px", "");
							$this.stop().animate({
                                width: space2 +"px"
                                }, {
                                queue: true,
                                duration: o.duration,
                                easing: o.captionEasing
                            })
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