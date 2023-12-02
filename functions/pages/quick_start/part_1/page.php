<style>
  div.auto-post-fade.hidden {
    display: none
  }
</style>
<div class="auto-post-welcome">
  <div class="auto-post-frame-wrapper">
    <div class="auto-post-frame">
      <div class="auto-post-iphone-pro auto-post-fade hidden">
        <!-- Tab content -->
        <div id="auto-post-step-1" class="auto-post-tabcontent">
          <div class="auto-post-logo"></div>
          <div class="auto-post-overlap-group">
            <div class="auto-post-angular-blur"></div>
            <div class="auto-post-content-wrapper">
              <div class="auto-post-content">
                <div class="auto-post-text-wrapper">Welcome to Autopost.ai</div>
                <p class="auto-post-div">
                  Your gateway to crafting high-quality, engaging content for your WordPress site through AI-powered
                  posts.
                </p>
                <div class="auto-post-frame-2">
                  <div class="auto-post-text-wrapper-2" onclick="window.history.back()">Skip</div>
                  <button class="auto-post-button" onclick="autoPostChangeStep('auto-post-step-2')">
                    <div class="auto-post-frame-3">
                      <span class="auto-post-span-2">Next</span>
                      <img class="auto-post-arrows-right-arrow"
                        src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg" />
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="auto-post-step-2" class="auto-post-tabcontent">
          <div class="auto-post-cfcdigital-dynamic"></div>
          <div class="auto-post-overlap-group">
            <div class="auto-post-angular-blur"></div>
            <div class="auto-post-frame">
              <div class="auto-post-content">
                <div class="auto-post-text-wrapper">Create Massively with AI</div>
                <p class="auto-post-div">
                  Kickstart your content creation journey. Use AI to generate articles effortlessly. Simply input your
                  prompts and topics of interest, and let Autopost do the writing for you.
                </p>
                <div class="auto-post-frame-2">
                  <div class="auto-post-text-wrapper-2 auto-post-tablinks"
                    onclick="autoPostChangeStep('auto-post-step-1')">Skip</div>
                  <button class="auto-post-button" onclick="autoPostChangeStep('auto-post-step-3')">
                    <div class="auto-post-frame-3">
                      <span class="auto-post-span-2">Next</span>
                      <img class="auto-post-arrows-right-arrow"
                        src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg" />
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="auto-post-step-3" class="auto-post-tabcontent">
          <div class="auto-post-cfcdigital-dynamic"></div>
          <div class="auto-post-overlap-group">
            <div class="auto-post-angular-blur"></div>
            <div class="auto-post-frame">
              <div class="auto-post-content">
                <div class="auto-post-text-wrapper">One-Click Social Media Publishing</div>
                <p class="auto-post-div">
                  Share your content with the world in an instant. Autopost enables you to publish directly to your
                  WordPress site and across your social media platforms, all in one.
                </p>
                <div class="auto-post-frame-2">
                  <div class="auto-post-text-wrapper-2" onclick="autoPostChangeStep('auto-post-step-2')">Skip</div>
                  <button class="auto-post-button" onclick="autoPostChangeStep('auto-post-step-4')">
                    <div class="auto-post-frame-3">
                      <span class="auto-post-span-2">Next</span>
                      <img class="auto-post-arrows-right-arrow"
                        src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg" />
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="auto-post-step-4" class="auto-post-tabcontent">
          <div class="auto-post-cfcdigital-dynamic"></div>
          <div class="auto-post-overlap-group">
            <div class="auto-post-angular-blur"></div>
            <div class="auto-post-frame">
              <div class="auto-post-content">
                <p class="auto-post-text-wrapper">Get Inspired with AI suggestions</p>
                <p class="auto-post-div auto-post-never-run-out-of">
                  Never run out of ideas. Autopost&#39;s AI-powered suggestion engine delivers fresh prompts and
                  trending
                  topics, ensuring you&#39;re never short of content inspiration.
                </p>
                <div class="auto-post-frame-2">
                  <div class="auto-post-text-wrapper-2" onclick="autoPostChangeStep('auto-post-step-3')">Skip</div>
                  <a href="<?php menu_page_url('chat_gpt_quick_start_part_2'); ?>">
                    <button class="auto-post-button">
                      <div class="auto-post-frame-2">
                        <span class="auto-post-span-2">Let’s setup</span>
                        <img class="auto-post-arrows-right-arrow"
                          src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg" />
                      </div>
                    </button>
                  </a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
