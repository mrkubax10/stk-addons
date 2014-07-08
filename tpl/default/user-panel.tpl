<ul class="nav nav-tabs" role="tablist" id="user-panel-nav">
    <li class="active"><a href="#profile" role="tab" data-toggle="tab">{t}Profile{/t}</a></li>
    <li><a href="#friends" role="tab" data-toggle="tab">{t}Friends{/t}</a></li>
    {if $can_see_settings}
        <li><a href="#settings" role="tab" data-toggle="tab">{t}Settings{/t}</a></li>
    {/if}
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="profile">
        <h1>{$user.username|escape}</h1>

        <div class="container">
            <div class="row form-group">
                <div class="col-md-3">{t}Username:{/t}</div>
                <div class="col-md-3" id="user-username">{$user.username|escape}</div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">{t}Registration Date:{/t}</div>
                <div class="col-md-3">{$user.date_registration|escape}</div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">{t}Real Name:{/t}</div>
                <div class="col-md-3" id="user-realname">{$user.real_name|escape}</div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">{t}Role:{/t}</div>
                <div class="col-md-3" id="user-role">{$user.role|escape}</div>
            </div>
            {if $can_see_email}
                <div class="row form-group">
                    <div class="col-md-3">{t}Email:{/t}</div>
                    <div class="col-md-3">{$user.email|escape}</div>
                </div>
            {/if}

            {$homepage_class=""}
            {if empty($user.homepage)}
                {$homepage_class=" hide"}
            {/if}
            <div class="row form-group{$homepage_class}" id="user-homepage-row">
                <div class="col-md-3">{t}Homepage:{/t}</div>
                <div class="col-md-3" id="user-homepage"><a href="{$user.homepage|escape}">{$user.homepage|escape}</a></div>
            </div>
        </div>
        <div>
            {foreach $user.addon_types as $addon_type}
                <div>
                    <h2>{$addon_type.heading}</h2>
                    {if !empty($addon_type.items)}
                        <ul>
                            {*the list is already filtered in the code*}
                            {foreach $addon_type.items as $item}
                                <li class="{$item.css_class}">
                                    <a href="addons.php?type={$addon_type.name}&amp;name={$item.id}">{$item.name|escape}</a>
                                </li>
                            {/foreach}
                        </ul>
                    {else}
                        {$addon_type.no_items}
                        <br>
                    {/if}
                </div>
            {/foreach}
        </div>

    </div>
    <div class="tab-pane" id="friends">

    </div>
    {if $can_see_settings}
        <div class="tab-pane" id="settings">
            <hr>
            <h3>{t}Profile{/t}</h3>
            <form class="form-horizontal" id="user-edit-profile">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="user-profile-homepage">
                        {t}Homepage{/t}
                    </label>
                    <div class="col-md-6">
                        <input type="text" name="homepage" id="user-profile-homepage" class="form-control" value="{$user.homepage|escape}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label" for="user-profile-realname">
                        {t}Real name{/t}
                    </label>
                    <div class="col-md-6">
                        <input type="text" name="realname" id="user-profile-realname" class="form-control" value="{$user.real_name|escape}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-2">
                        <input type="hidden" name="user-id" value="{$user.user_id}">
                        <input type="hidden" name="action" value="edit-profile">
                        <input type="submit" class="btn btn-success" value="{t}Save profile{/t}">
                    </div>
                </div>
            </form>
            <hr>
            {if $can_edit_role}
                <h3>Edit user</h3>
                <form class="form-horizontal" id="user-edit-role">
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="user-settings-role">
                            {t}Role{/t}
                        </label>
                        <div class="col-md-6">
                            <select class="form-control" id="user-settings-role" name="role">
                                {html_options options=$user.settings.elevate.options selected=$user.settings.elevate.selected|default:""}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="user-settings-available" name="available" {$user.settings.elevate.activated|default:""}> {t}User Activated{/t}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-2">
                            <input type="hidden" name="user-id" value="{$user.user_id}">
                            <input type="hidden" name="action" value="edit-role">
                            <input type="submit" class="btn btn-warning" value="{t}Edit{/t}">
                        </div>
                    </div>
                </form>
                <hr>
            {/if}
            {if $is_owner}
                <h3>{t}Change Password{/t}</h3>
                <br>
                <form class="form-horizontal" id="user-change-password">
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="user-settings-old-pass">
                            {t}Old Password{/t}<br>
                        </label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="user-settings-old-pass" name="old-pass">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="user-settings-new-pass">
                            {t}New Password{/t}
                        </label>
                        <div class="col-md-6">
                            <input type="password" name="new-pass" id="user-settings-new-pass" class="form-control">
                        </div>
                        <span class="help-block">
                            ({t 1=8}Must be at least %1 characters long{/t})
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="user-settings-new-pass-verify">
                            {t}New Password (Confirm){/t}<br>
                        </label>
                        <div class="col-md-6">
                            <input type="password" name="new-pass-verify" id="user-settings-new-pass-verify" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-2">
                            <input type="hidden" name="user-id" value="{$user.user_id}">
                            <input type="hidden" name="action" value="change-password">
                            <input type="submit" class="btn btn-warning" value="{t}Change Password{/t}">
                        </div>
                    </div>
                </form>
            {/if}
        </div>
    {/if}
</div>