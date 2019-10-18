// http://chrisbateman.github.io/impetus/
// https://github.com/chrisbateman/impetus
// Peep at line 220 to see what options are being passed to the constructor

;(function() {
    "use strict";

	var Impetus = function(elem, cfg) {

		var source, updateCallback;
		var multiplier = 1;
		var targetX = 0;
		var targetY = 0;
		var friction = 0.92;
		var preventDefault = true;
		var boundXmin, boundXmax, boundYmin, boundYmax;

		var ticking = false;
		var pointerActive = false;
		var paused = false;
		var trackingPoints = [];
		var pointerLastX;
		var pointerLastY;
		var pointerCurrentX;
		var pointerCurrentY;
		var pointerId;

		var decelerating = false;
		var decVelX;
		var decVelY;


		var requestAnimFrame = (function(){
			return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(callback) {
				setTimeout(callback, 1000 / 60);
			};
		})();


	    var on = function (elem, event, fn) {
	        elem.addEventListener ? elem.addEventListener(event, fn, false) : elem.attachEvent('on'+event, function () {
	            fn.apply(elem, arguments);
	        });
	    };


		var updateTarget = function() {
			updateCallback(targetX * multiplier, targetY * multiplier);
		};

		this.pause = function() {
			pointerActive = false;
			paused = true;
		};

		this.unpause = function() {
			paused = false;
		};

		var onDown = function(ev) {
			if (pointerActive && paused) return;
	        pointerActive = true;
	        decelerating = false;
	        pointerId = ev.pointerId;

	        pointerLastX = pointerCurrentX = ev.clientX;
	        pointerLastY = pointerCurrentY = ev.clientY;
	        trackingPoints = [];
	        addTrackingPoint(pointerLastX, pointerLastY, new Date());
		};

		var onMove = function(ev) {
	        if (preventDefault) ev.preventDefault ? ev.preventDefault() : ev.returnValue = false;

	        if (pointerActive && ev.pointerId === pointerId) {
				pointerCurrentX = ev.clientX;
				pointerCurrentY = ev.clientY;
				addTrackingPoint(pointerLastX, pointerLastY, new Date());
				requestTick();
			}
		};

		var onUp = function(ev) {
			if (pointerActive && ev.pointerId === pointerId) {
				pointerActive = false;
				addTrackingPoint(pointerLastX, pointerLastY, new Date());
				startDecelAnim();
			}
		};


		var addTrackingPoint = function(x, y, time) {
			while (trackingPoints.length) {
				if (time - trackingPoints[0].time <= 100) break;
				trackingPoints.shift();
			}

			trackingPoints.push({
				x: x,
				y: y,
				time: time
			});
		};

		var update = function() {
			targetX += pointerCurrentX - pointerLastX;
			targetY += pointerCurrentY - pointerLastY;

			checkBounds();
			updateTarget();

			pointerLastX = pointerCurrentX;
			pointerLastY = pointerCurrentY;
			ticking = false;
		};

		var requestTick = function() {
			if (!ticking) requestAnimFrame(update);
			ticking = true;
		};

		var checkBounds = function() {
			if (targetX < boundXmin) targetX = boundXmin;
			if (targetX > boundXmax) targetX = boundXmax;
			if (targetY < boundYmin) targetY = boundYmin;
			if (targetY > boundYmax) targetY = boundYmax;
		};


		var startDecelAnim = function() {
			var firstPoint = trackingPoints[0];
			var lastPoint = trackingPoints[trackingPoints.length - 1];

			var xOffset = lastPoint.x - firstPoint.x;
			var yOffset = lastPoint.y - firstPoint.y;
			var timeOffset = lastPoint.time - firstPoint.time;

			var D = timeOffset / 15;

			decVelX = xOffset / D;
			decVelY = yOffset / D;

			if (Math.abs(decVelX) > 1 || Math.abs(decVelY) > 1) {
				decelerating = true;
				requestAnimFrame(stepDecelAnim);
			}
		};

		var stepDecelAnim = function() {
			if (!decelerating) return;

			decVelX *= friction;
			decVelY *= friction;

			if (Math.abs(decVelX) > 0.5 || Math.abs(decVelY) > 0.5) {
				targetX += decVelX;
				targetY += decVelY;

				if (checkBounds()) {
					decelerating = false;
				}
				updateTarget();

				requestAnimFrame(stepDecelAnim);
			} else {
				decelerating = false;
			}
		};



		(function init() {
			if (elem) {
				elem = (typeof elem === 'string') ? document.querySelector(elem) : elem;
				if (!elem) throw new Error('IMPETUS: source not found.');
			} else {
				elem = document;
			}

			if (cfg.update) {
				updateCallback = cfg.update || updateCallback;
			} else {
				throw new Error('IMPETUS: update function not defined.');
			}

			multiplier = cfg.multiplier || multiplier;
			friction = cfg.friction || friction;
			preventDefault = cfg.preventDefault || preventDefault;


			if (cfg.startPos) {
				if (cfg.startPos[0]) {
					targetX = cfg.startPos[0] / multiplier;
				}
				if (cfg.startPos[1]) {
					targetY = cfg.startPos[1] / multiplier;
				}
				updateTarget();
			}

			if (cfg.boundX) {
				boundXmin = cfg.boundX[0] / multiplier;
				boundXmax = cfg.boundX[1] / multiplier;
			}
			if (cfg.boundY) {
				boundYmin = cfg.boundY[0] / multiplier;
				boundYmax = cfg.boundY[1] / multiplier;
			}


	        on(elem, 'mousedown', onDown);
	        on(document, 'mousemove', onMove);
	        on(document, 'mouseup', onUp);

		}());

	};

	function horizontalScroll() {
    var scrolls = document.querySelectorAll('.gallery__draggable');
  	if (typeof(scrolls) != 'undefined' && scrolls != null && scrolls.length > 0) {
  	    Array.prototype.forEach.call(scrolls, function(scroll) {
  	        const maxScroll = scroll.scrollWidth - scroll.offsetWidth;
  	        // console.log(scroll.offsetWidth)
  	        new Impetus(scroll, {
  	        	boundX: [-maxScroll, 0],
  	          update: (x) => {
  	            if (scroll) {
  	            	scroll.scrollLeft = -x;
  	            }
  	          },
  	        });
  	    });
  	}
  }

  horizontalScroll();

  // Let's listen for the resize event
  window.addEventListener('resize', onWindowResize);
  function onWindowResize(e) {
   // Throttle down the number of times it is called
   // Only start the animation again when the resize has ended
   horizontalScroll();
  }

})();
