
<div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            <div class="sidebar-header">
                <a href="#"><img class="main-logo" src="<?=base_url()?>dash_assets/img/logo/logo1.png" alt="" /></a>
                <strong><a href="#"><img src="<?=base_url()?>dash_assets/img/logo/logosn.png" alt="" /></a></strong>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1">
                        <script>
                            function clickTabId(id){
                                id= id.trim();
                              //alert(id);
                              $("#"+id).click();
                            }
                        </script>
                        <?php

                        $side_bar_values = array();
                        array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Profile',
                            'link' => $profile_url,
                            'class' =>'educate-icon educate-professor icon-wrap'));
                        array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Logout',
                            'link' => base_url()."logout",
                            'class' =>'educate-icon educate-pages icon-wrap'));

                        foreach($side_bar_values as $side_bar_value) {
                            if ($side_bar_value['is_tab_base'] == 'N') {
                                if ($side_bar_value['is_submenu'] == 0) {
                                    ?>

                                    <li style="max-width:210px">
                                        <a title="Landing Page" href="<?= $side_bar_value['link']; ?>"
                                           aria-expanded="false"><span class="<?= $side_bar_value['class']; ?>"
                                                                       aria-hidden="true"></span> <span
                                                    class="mini-click-non"><?= $side_bar_value['value']; ?></span></a>
                                    </li>

                                    <?php
                                }
                                else if ($side_bar_value['is_submenu'] == 1) {
                                    ?>
                                    <li style="max-width:210px">
                                        <a class="has-arrow" href="<?= $side_bar_value['link']; ?>">
                                            <span class="<?= $side_bar_value['class']; ?>"></span>
                                            <span class="mini-click-non"><?= $side_bar_value['value']; ?></span>
                                        </a>
                                        <ul class="submenu-angle" aria-expanded="true">
                                            <?php
                                            foreach ($side_bar_value['sub_menu'] as $sub_menu) {
                                                ?>
                                                <li><a title="<?= $sub_menu['value']; ?>"
                                                       href="<?= $sub_menu['link']; ?>"><span
                                                                class="mini-sub-pro"><?= $sub_menu['value']; ?></span></a>
                                                </li>

                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                   
                                    <?php
                                }
                            }
                            else if($side_bar_value['is_tab_base'] == 'Y'){
                               // echo "$script_name "."------".$side_bar_value['link'];
                                if($script_name==$side_bar_value['link']){


                                    ?>

                                    <li class="active" style="max-width:210px">
                                        <a class="has-arrow" href="<?=$side_bar_value['link']?>" aria-expanded="true"><span class="<?= $side_bar_value['class']; ?>"></span> <span class="mini-click-non"><?= $side_bar_value['value']; ?></span></a>
                                        <ul class="submenu-angle collapse show" aria-expanded="false" style="">
                                            <?php
                                            for($i=0;$i<count($tab_list);$i++) {
                                                $tab = $tab_list[$i];

                                                ?>
                                                <li><a title="All Professors" href="#" onclick="clickTabId('<?=$tab['LINK_ID']?>')"><span
                                                                class="mini-sub-pro"><?=$tab['TAB_NAME']?></span></a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                }
                                else{
                                    ?>
                                    <li style="max-width:210px">
                                        <a class="has-arrow" title="Landing Page" href="<?=$side_bar_value['link']?>" aria-expanded="false"><span class="<?= $side_bar_value['class']; ?>" aria-hidden="true"></span> <span class="mini-click-non"><?= $side_bar_value['value']; ?></span></a>
                                    </li>
                                    <?php
                                }

                            }
                           // echo "<hr>";
                        }

                        ?>



                    </ul>
                </nav>
            </div>
        </nav>
    </div>