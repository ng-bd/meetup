;(function() {
  'use strict';

  // var scroll = new SmoothScroll('a[href*="#"]');

  Draggable.create(".gallery__draggable", {
    bounds: ".gallery__draggable",
    type: "scrollLeft",
    edgeResistance: 0.85,
    throwProps: true
  })

})();