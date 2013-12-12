<nav id="main-navigation" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span>
                <span class="icon-bar"></span> <span class="icon-bar"></span>
            </button>
            <figure class="logo">
                <a class="navbar-brand" href="#"><img src="<?php __pc('customer_logo_square'); ?>" alt="logo" title=""></a>
            </figure>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-main-collapse">
            <ul class="nav navbar-nav">
                <!--li class="home"><a href="#"><?php __p('navigation_tab_home') ?></a></li-->
                <?php
                $custom_tabs = explode(',', __c('navigation_pagetab_selector'));
                $tabs = array();
                $tabs[] = array(
                    'url'   => '#/',
                    'class' => 'home',
                    'text'  => __t('home')
                );

                if (!empty($custom_tabs[0]))
                {
                    foreach ($custom_tabs AS $identifier)
                    {
                        if (strpos($identifier, 'navigation_tab_') !== false)
                        {
                            $url = __c($identifier . '_link_intern');
                            if ($identifier === 'navigation_tab_1' || $identifier === 'navigation_tab_2' || $identifier === 'navigation_tab_3')
                            {
                                $external_link = __c($identifier . '_link_extern');
                                if (!empty($external_link))
                                {
                                    $url = $external_link;
                                }
                            }

                            $tabs[] = array(
                                'url'   => $url,
                                'class' => $identifier,
                                'text'  => __c($identifier . '_name')
                            );
                        }

                        if ($identifier === 'imprint' || $identifier === '' || $identifier === 'privacy' || $identifier === 'terms')
                        {
                            $tabs[] = array(
                                'url'   => '#/page/app/' . $identifier,
                                'class' => 'app-' . $identifier,
                                'text'  => __t($identifier)
                            );
                        }
                    }
                }

                // add greetingcards
                if (__c('greetingcard_activated') === '1')
                {
                    $tabs[] = array(
                        'url'   => '#/page/greetingcards',
                        'class' => 'greetingcards',
                        'text'  => __t('tab-greetingcards')
                    );
                }
                // add profile
                if (__c('greetingcard_activated') === '1')
                {
                    $tabs[] = array(
                        'url'   => '#/page/profile',
                        'class' => 'nav-profile ' . $show_profil,
                        'text'  => __t('profile')
                    );
                }
                // only for desktop
                ?>
                <li id="nav-more" class="dropdown hidden-xs">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-align-justify"></i> <?php __p('navi'); ?>
                        <b class="caret"></b></a>

                    <ul class="dropdown-menu" role="menu">
                    <?php foreach ($tabs AS $tab): ?>
                            <li class="link-element">
                                <a href="<?php echo $tab['url'] ?>" class="<?php echo $tab['class'] ?>"><?php echo $tab['text'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <?php
                // only for mobile version
                foreach ($tabs AS $tab):
                    ?>
                    <li class="visible-xs link-element">
                        <a href="<?php echo $tab['url'] ?>" class="<?php echo $tab['class'] ?>"><?php echo $tab['text'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li id="nav-login" class="link-element <?php echo $show_login ?>">
                    <a href="#/page/participate"><i class="icon-signin"></i> <?php __p('login'); ?></a>
                </li>
                <li id="nav-admin" class="nav-admin link-element <?php echo $show_admin ?>">
                <a href="#" class="btn btn-danger"><i class="icon-wrench"> </i><? __p('admin'); ?></a>
                </li>
                <li id="nav-profile" class="nav-profile <?php echo $show_profil ?>">
                    <a href="#page/profile"><img src="https://secure.gravatar.com/avatar/<?php echo md5(strtolower($user)) ?>?s=40&amp;d=mm" alt="avatar"></a>

                    <!--ul class="dropdown-menu" aria-labelledby="dropdownMore" role="menu">
                        <?php //echo $show_admin ?>
                        <li class="nav-admin link-element" id="admin">
                            <a href="#"><i class="icon-wrench"> </i><? __p('admin'); ?></a>
                        </li>
                        <li class="link-element">
                            <a href="#page/profile"><i class="icon-user"></i> <?php echo __p('profile') ?></a>
                        </li>
                        <li class="link-element">
                            <a href="#page/profile/logout"><i class="icon-signout"></i> <?php echo __p('logout') ?></a>
                        </li>
                    </ul-->
                </li>
                <li class="link-element nav-logout  <?php echo $show_logout ?>">
                <a href="#page/profile/logout"><i class="icon-signout"></i> <?php echo __p('logout') ?></a>
                </li>
                <?php
                $languages = explode(',', __c('language_selection'));
                if (is_array($languages) && count($languages) > 1):
                    ?>
                    <li id="nav-lang" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="flag-sprite flag-<?php echo $cur_locale; ?>"></span>
                            <?php echo ($device === 'mobile') ? __t('language') : '' ?> <b class="caret"></b></a>

                        <ul class="dropdown-menu <?php echo ($device !== 'mobile') ? 'pull-right' : '' ?>" role="menu">
                            <?php
                            foreach ($languages AS $locale):
                                if ($cur_locale !== $locale):
                                    $param_name  = 'locale';
                                    $param_value = $locale;
                                    if ($aa->env->base !== 'website')
                                    {
                                        $param_name  = 'app_data';
                                        $param_value = urlencode('{"locale":"' . $locale . '"}');
                                    }
                                    ?>
                                    <li class="link-element">
                                        <a href="<?php echo $aa->instance->share_url ?>&amp;page=<?php echo $aa->env->base; ?>&amp;<?php echo $param_name . '=' . $param_value; ?>"><span class="flag-sprite flag-<?php echo $locale; ?>"></span> <?php __p('lang_' . $locale) ?>
                                        </a>
                                    </li>
                                <?php
                                endif;
                            endforeach;
                            ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php if (!empty($aa->fb->is_fb_user_admin)): ?>
    <div class="alert alert-info alert-dismissable admin-fb-info">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php
        $info = __t('fb_admin_info');
        $link1 = '<a href="#page/participate">' . __t('fb_admin_info_link1') . '</a>';
        $link2 = '<a href="https://manager.app-arena.com/instances/' . $aa_inst_id . '/wizard" target="_blank">' . __t('fb_admin_info_link2') . '</a>';
        printf($info, $link1, $link2);
        ?>
    </div>
<?php ENDIF; ?>