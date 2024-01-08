<!-- Settings.tpl -->
<script type="module" src="{$mainJsPath}"></script>
<link rel="stylesheet" href="{$mainCssPath}" type="text/css">

<form method="post" id="landswitcher_form" class="form-horizontal">
    {$nonceField}
    <div class="form-group">
        <label for="landswitcher_country" class="control-label col-sm-2">Country:</label>
        <div class="col-sm-10">
            <select name="landswitcher_country" id="landswitcher_country" class="form-control">
                {foreach from=$countries item=country}
                    <option value="{$country->cISO}">{$country->{$currentLanguage}}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="landswitcher_redirect_url" class="control-label col-sm-2">Redirect URL:</label>
        <div class="col-sm-10">
            <input type="text" id="landswitcher_redirect_url" name="landswitcher_redirect_url" value=""
                class="form-control">
            <datalist id="url_list">
                {foreach from=$redirects item=redirect}
                    <option data-iso="{$redirect->cISO}" value="{$redirect->url}"></option>
                {/foreach}
            </datalist>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" id="submit_button" value="Save" class="btn btn-primary">
            <div id="loading_spinner" style="display: none;"><i class="fas fa-spinner"></i></div>
            <div id="message_container" style="display: none;"></div>
        </div>
    </div>
</form>