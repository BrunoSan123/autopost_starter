<style>
    div.auto-post-fade.hidden {
        display: none
    }
</style>
<div class="auto-post-frame">
    <div class="auto-post-blur"></div>
    <div class="auto-post-fade hidden">
        <div id="auto-post-step-5" class="auto-post-tabcontent">
            <div class="auto-post-div">

                <div class="auto-post-title-desc">
                    <div class="auto-post-div-2">
                        <p class="auto-post-text-wrapper">Choose your prefered AI APIs</p>
                        <p class="auto-post-p">Don’t worry, you can change it or add new APIs later in settings tab.
                        </p>
                    </div>
                </div>
                <div class="auto-post-content">
                    <div class="auto-post-content-2">
                        <div class="auto-post-frame-wrapper">
                            <div class="auto-post-div-3" id="select_image_gen">
                                <div class="auto-post-div-4">
                                    <p class="auto-post-select-an-API-to">
                                        <span class="auto-post-span">Select an API to generate </span> <span
                                            class="auto-post-text-wrapper-2">Images</span>
                                    </p>
                                    <div class="auto-post-div-5">
                                        <div class="auto-post-div-6">
                                            <div class="auto-post-text-wrapper-3">Midjourney</div>
                                            <input type="radio" class="auto-post-radio-md input_value" id="images-api-1" name="images-api" value="Midjourney" />
                                        </div>
                                        <div class="auto-post-div-6">
                                            <div class="auto-post-text-wrapper-3">DALL-E</div>
                                            <input type="radio" class="auto-post-radio-md input_value" id="images-api-2" name="images-api" value="DALL-E" />

                                        </div>
                                    </div>
                                </div>
                                <div class="auto-post-div-4">
                                    <p class="auto-post-select-an-API-to">
                                        <span class="auto-post-span">Select an API to generate </span> <span
                                            class="auto-post-text-wrapper-2">Text</span>
                                    </p>
                                    <div class="auto-post-div-5">
                                        <div class="auto-post-div-6">
                                            <div class="auto-post-text-wrapper-3">GPT-3</div>
                                            <input type="radio" class="auto-post-radio-md input_value" id="text-api-1" name="text-api" value="GPT-3" />
                                        </div>
                                        <div class="auto-post-div-6">
                                            <div class="auto-post-text-wrapper-3">GPT-4</div>
                                            <input type="radio" class="auto-post-radio-md input_value" id="text-api-2" name="text-api" value="GPT-4" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="auto-post-div-7">
                            <button class="auto-post-button" onclick="window.history.back()"><span
                                    class="auto-post-span-2">I’ll do it
                                    later</span></button>
                            <button class="auto-post-div-wrapper modal-btn" onclick="autoPostChangeStep('auto-post-step-6'),setLocalStorageItemModal()">
                                <div class="auto-post-div-8">
                                    <span class="auto-post-span-3">Next</span> <img class="auto-post-arrows-right-arrow"
                                        src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg"/>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div id="auto-post-step-6" class="auto-post-tabcontent">
            <div class="iphone-pro">
                <div class="content">
                    <div class="div" id="api_key">
                        <div class="div-2">
                            <div class="text-wrapper">Setup your APIs</div>
                            <p class="p">Insert your own APIs to use in Autopost</p>
                        </div>
                        <div class="frame-wrapper">
                            <div class="input-text-wrapper">
                                <div class="input-text">
                                    <div class="input-label">Midjourney API Key</div>
                                    <textarea name="Midjourney API Key" id="midjourney_key" class="input-invite input_value"  rows="4" cols="50" placeholder="Insert Your API Key Here"><?php echo(get_option("midjourney_api_key"));?></textarea>
                                    <p class="helper-text-input">
                                        <span class="span">Sign up or log in to MidJourney, get your API key and
                                            paste
                                            above
                                            - </span>
                                        <a href="https://www.midjourney.com/signup/" target="_blank"
                                            rel="noopener noreferrer"><span class="text-wrapper-2">Click here to get
                                                your
                                                API key</span></a>
                                        <span class="text-wrapper-3">.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="frame-wrapper">
                            <div class="input-text-wrapper">
                                <div class="input-text">
                                    <div class="input-label">GPT-3 API Key</div>
                                    <textarea name="GPT-3 API Key" id="gpt_key" class="input-invite input_value"  rows="4" cols="50" placeholder="Insert Your API Key Here"><?php echo(get_option("chatgpt_api_key"));?></textarea>
                                    <p class="helper-text-input">
                                        <span class="span">Sign up or log in to OpenAI, get your GPT-3 API key and
                                            paste
                                            above - </span>
                                        <a href="https://www.openai.com/signup/" target="_blank"
                                            rel="noopener noreferrer"><span class="text-wrapper-2">Click here to get
                                                your
                                                API key</span></a>
                                        <span class="text-wrapper-3">.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="div-3">
                        <button class="button" onclick="autoPostChangeStep('auto-post-step-5')"><span
                                class="span-2">Back</span></button>
                        <button class="div-wrapper modal-btn" onclick="autoPostChangeStep('auto-post-step-7'),setLocalStorageItemModal()">
                            <div class="div-4">
                                <span class="span-3">Next</span> <img class="arrows-right-arrow"
                                    src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg" />
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="auto-post-step-7" class="auto-post-tabcontent">
            <div class="iphone-pro">
                <div class="content">
                    <div class="div">
                        <div class="div-2">
                            <p class="text-wrapper">How experienced are you by using AI generators?</p>
                            <p class="p">This information will be used to customize your experience</p>
                        </div>
                        <div class="div-3" id="experience">
                            <div class="div-4">
                                <input type="radio" class="radio-md input_value" name="experience" value="No Experience" />
                                <div class="div-5">
                                    <div class="div-wrapper">
                                        <div class="text-wrapper-2 input_value">No Experience</div>
                                    </div>
                                    <p class="text-wrapper-3">I&#39;ve never used AI generators before. This is all
                                        new
                                        to
                                        me.</p>
                                </div>
                            </div>
                            <div class="div-4">
                                <input type="radio" class="radio-md input_value"  name="experience" value="Some Experience" />
                                <div class="div-5">
                                    <div class="div-wrapper">
                                        <div class="text-wrapper-4 input_value">Some Experience</div>
                                    </div>
                                    <p class="text-wrapper-3">I&#39;ve dabbled with AI generators a bit, but I&#39;m
                                        not
                                        an
                                        expert.</p>
                                </div>
                            </div>
                            <div class="div-4">
                                <input type="radio" class="radio-md input_value" name="experience" value="Experienced" />
                                <div class="div-5">
                                    <div class="div-wrapper">
                                        <div class="text-wrapper-5 input_value">Experienced</div>
                                    </div>
                                    <p class="text-wrapper-3">
                                        I&#39;m quite experienced with AI generators and have used them extensively.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="div-6">
                        <button class="button" onclick="autoPostChangeStep('auto-post-step-6')"><span
                                class="span-2">Back</span></button>
                        <button class="frame-wrapper modal-btn" onclick="autoPostChangeStep('auto-post-step-8'),setLocalStorageItemModal()">
                            <div class="div-7">
                                <span class="span-3">Next</span> <img class="arrows-right-arrow"
                                    src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg" />
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="auto-post-step-8" class="auto-post-tabcontent">
            <div class="iphone-pro">
                <div class="content">
                    <div class="div">
                        <div class="div-wrapper">
                            <p class="text-wrapper">What topics do you want to create content about?</p>
                        </div>
                        <div class="frame-wrapper">
                            <div class="input-text-wrapper">
                                <div class="input-text">
                                    <p class="input-label">Please enter your preferred topics below.</p>
                                    <textarea name="preferred-topics" class="input-invite input_value"  rows="4" cols="50" placeholder="Ex: I want to write about chocolate recipes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="div-2">
                        <button class="button" onclick="autoPostChangeStep('auto-post-step-7')"><span
                                class="span-2">Back</span></button>
                        <button class="button-3" onclick="setLocalStorageItemModal();finishQuickStart()">
                            <div class="div-3">
                                <span class="span-4">Start Autopost</span>
                                <img class="arrows-right-arrow"
                                    src="<?php echo plugin_dir_url(__FILE__); ?>../assets/img/right-arrow.svg" />
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .auto-post-blur {
        width: 100%;
        height: 100vh;
        backdrop-filter: blur(5px);
    }
</style>