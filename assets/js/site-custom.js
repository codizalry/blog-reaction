jQuery(document).ready(function() {
  setTimeout(() => {
    jQuery(".ri-radio-btn").on('click', function (e) {
      var previousValue = jQuery(this).attr('previousValue');
      if (previousValue == 'true') {
          this.checked = false;
          jQuery(this).attr('previousValue', this.checked);
      }
      else {
          this.checked = true;
          jQuery(this).attr('previousValue', this.checked);
      }
    });
  }, 1000);
});

(function($) {
  $(document).ready(function() {

    var baseUrl = "blog_reaction";
    var pageViewSent = false;
    var wpUrl = blog_reaction_data.ajax_url;
    var startingTime;
    var timeout = 60000;
    var active = true;

    function Plugin(elem, posts) {
      var self = this;
      self.elem = elem;
      self.posts = posts;
      self.id = elem.data('postId');
      self.reactions = {};
      self.clickedReactions = [];

      // Check which reactions has been clicked
      $.each(posts, function(reaction, amount) {
        self.reactions[reaction] = amount
        if (Cookies.get('blog_reaction_reacted_' + reaction + '_' + self.id)) {
          self.clickedReactions.push(reaction);
        }
      });

      // Add clicked class
      self.elem.find('.ri-inner-items')
        .filter(function() {
          var reaction = $(this).data('reaction');
          return isClicked(reaction);
        })
        .addClass('clicked');

        //Blocking the reaction button
        jQuery(document).ready(function(){
          if (jQuery(".ri-inner-items").hasClass('clicked')) {
            jQuery(".ri-inner-items").siblings('div').addClass('blocked');
            jQuery(".ri-inner-items.clicked").removeClass('blocked');
          }
          jQuery(".ri-inner-items").click(function(e){
            if (jQuery(this).hasClass('clicked')) {
              jQuery(this).removeClass('blocked');
              jQuery(this).siblings('div').addClass('blocked');
            } else {
              jQuery(this).siblings('div').removeClass('blocked');
            }
          });
        });





      // Add reaction amounts
      self.elem.find('.ri-inner-items').each(function() {
        var reaction = $(this).data('reaction');
        $(this).find('.ri-badge').text(self.reactions[reaction]);
      });
      self.postUrl = self.elem.data('postUrl');
      self.separator = getSeparator(self.postUrl);
      var featureImg = self.elem.data('postImg');
      var ogImg = document.querySelectorAll("meta[property='og:image']")[0] ? document.querySelectorAll("meta[property='og:image']")[0].content : '';

      function getSeparator(url) {
        var urlArray = url.split('/');
        if (urlArray[urlArray.length - 1].indexOf('?') === -1) {
          return '?';
        }
        return '&';
      }

      function setReadingTime(trigger) {
        var now = Date.now();
        var readingTime = now - startingTime;
        if (active) {
          var readingTimeUrl = baseUrl;
          readingTimeUrl += '&a=time';
          readingTimeUrl += "&cu=" + encodeURIComponent(elem.data('postUrl'));
          readingTimeUrl += '&r=' + encodeURIComponent(document.referrer);;
          readingTimeUrl += '&rt=' + readingTime;
          readingTimeUrl += '&tr=' + trigger;
          sendPixel(readingTimeUrl);
          active = false;
        }
      }

      function resetStartingTime() {
        active = true;
        startingTime = Date.now();
      }

      function isClicked(reaction) {
        return self.clickedReactions.indexOf(reaction) !== -1;
      }

      function sendPixel(dataUrl) {
        if (blog_reaction_data.api_key && blog_reaction_data.api_key.length > 0) {
          var img = new Image();
          img.src = dataUrl;
        }
      };

      function react(event) {
        event.preventDefault();
        var elem = $(this);
        var unreact = (elem.hasClass("clicked") ? true : false);
        var reaction = elem.data().reaction;
        $.post(wpUrl, {
          postid: self.id,
          action: 'blog_reaction_react',
          reaction: reaction,
          unreact: unreact
        }, function(data) {

        });
        var cookieKey = 'blog_reaction_reacted_' + reaction + '_' + self.id;
        if (unreact) {
          Cookies.remove(cookieKey);
        } else {
          Cookies.set(cookieKey, 'true', {
            expires: 30
          });
        }

        elem.toggleClass("clicked");

        if (baseUrl) {
          var reactDataUrl = baseUrl + "&r=" + encodeURIComponent(document.referrer);
          reactDataUrl += "&a=reaction";
          reactDataUrl += "&cu=" + encodeURIComponent(self.elem.data('postUrl'));
          reactDataUrl += "&rd=" + Date.now();
          sendPixel(reactDataUrl);
        }

        var howMany = parseInt(elem.find('.ri-badge').text());
        if (howMany > 0) {
          if (elem.hasClass("clicked")) {
            howMany += 1;
          } else {
            howMany -= 1;
          }
        } else {
          howMany = 1;
        }
        elem.find('.ri-badge').text(howMany);
      }

      function getClickedReactions(elem) {
        return $(elem).closest(".d-reactions").find(".clicked");
      }

      self.elem.find('.ri-inner-items').click(react);
    }

    function initPlugin() {
      var posts = [];
      var postIds = []
      $.get(wpUrl, {
        action: 'blog_reaction_get_html'
      }, function(response) {
        $('.d-reactions').each(function() {
          this.addEventListener("touchstart", function() {}, true);
          $(this).html(response);
          postIds.push($(this).data('postId'));
          posts.push($(this));
        });

        $.post(wpUrl, {
            action: 'blog_reaction_get_reactions',
            posts: postIds
          },
          function(response) {
            $.each(posts, function(index, post) {
              var id = post.data('postId');
              var plugin = new Plugin(post, response[id])
            });
          }
        );
      });
    }
    initPlugin();
  });

})(jQuery);
