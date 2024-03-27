(function () {
  var DIV = "div";
  var BUTTON = "button";
  var SPAN = "span";
  var P = "p";
  var A = "a";

  window.addEventListener("load", function () {
    initSteppedProgress();
  });

  function initSteppedProgress() {
    [].forEach.call(
      document.querySelectorAll("[data-stepped-bar]"),
      function (steppedProgress, index) {
        if (steppedProgress) {
          var valueTotal = 0;
          var data;
          if (steppedProgress.getAttribute("data-stepped-bar")) {
            data = JSON.parse(steppedProgress.getAttribute("data-stepped-bar"));
          }

          //#region: Markup

          var title = createElementWithClass(P, "syncro-card-title");
          title.textContent = data.title;

          var step = createElementWithClass(DIV, "syncro-progress-stepped");

          var row = createElementWithClass(DIV, "syncro-row");

          data.catagories.forEach(function (catagory, i) {
            valueTotal += catagory.value;
          });

          data.catagories.forEach(function (catagory, i) {
            stepItem = createElementWithClass(
              DIV,
              "syncro-progress-stepped-item"
            );
            stepItem.setAttribute(
              "data-id",
              "progress-stepped-item-" + index + "-" + i
            );
            stepItem.textContent = catagory.value;
            stepItem.style.width = (catagory.value / valueTotal) * 100 + "%";
            stepItem.style.backgroundColor = catagory.color;

            step.appendChild(stepItem);

            var dot = createElementWithClass(SPAN, "syncro-dot");
            dot.style.backgroundColor = catagory.color;

            var category = createElementWithClass(SPAN, "syncro-category-name");
            category.textContent = catagory.name;

            var btn = createElementWithClass(BUTTON, "syncro-btn");
            btn.setAttribute(
              "data-target",
              "progress-stepped-item-" + index + "-" + i
            );
            btn.appendChild(dot);
            btn.appendChild(category);

            var col = createElementWithClass(DIV, "syncro-col-auto value-"+catagory.value);
            col.appendChild(btn);

            row.appendChild(col);
          });

          var cardBody = createElementWithClass(DIV, "syncro-card-body");
          cardBody.appendChild(title);
          cardBody.appendChild(step);
          cardBody.appendChild(row);

          var card = createElementWithClass(DIV, "syncro-card");
          card.appendChild(cardBody);

          var markup = createElementWithClass(DIV);
          markup.appendChild(card);

          steppedProgress.innerHTML = markup.innerHTML;

          //#endregion: Markup
          if (jQuery(".syncro-col-auto").hasClass('value-0')) {
            jQuery(".syncro-col-auto.value-0").remove();
          }
          jQuery(".syncro-progress-stepped-item").each(function(){
            if (jQuery(this).html() == "0"){
              jQuery(this).remove();
            }
          });
          //#region:
          [].forEach.call(
            steppedProgress.querySelectorAll(".syncro-progress-stepped-item"),
            function (el) {
              el.addEventListener("mouseenter", (e) => {
                toggleActive(e, el);
              });
              el.addEventListener("mouseleave", (e) => {
                toggleActive(e, el);
              });
            }
          );
          [].forEach.call(
            steppedProgress.querySelectorAll(".syncro-btn"),
            function (el) {
              el.addEventListener("click", function () {
                const dataID = el.getAttribute("data-target");
                var targetElm = document.querySelector(
                  '[data-id="' + dataID + '"]'
                );

                if (targetElm.classList.contains("active")) {
                  targetElm.classList.remove("active");
                } else {
                  [].forEach.call(
                    steppedProgress.querySelectorAll(
                      ".syncro-progress-stepped-item"
                    ),
                    function (el) {
                      el.classList.remove("active");
                    }
                  );
                  targetElm.classList.add("active");
                }
              });
            }
          );
          //#endregion
        }
      }
    );
  }

  function toggleActive(e, el) {


    if (e.type === "mouseenter") {
      if (!el.classList.contains("active")) {
        el.classList.add("active");
      }
    } else if (e.type === "mouseleave") {
      if (el.classList.contains("active")) {
        el.classList.remove("active");
      }
    }
  }

  function createElementWithClass(element, className = "") {
    var ele = document.createElement(element);
    if (className) {
      var classList = className.split(" ");
      classList.forEach(function (value, index) {
        ele.classList.add(value);
      });
    }
    return ele;
  }
})();
