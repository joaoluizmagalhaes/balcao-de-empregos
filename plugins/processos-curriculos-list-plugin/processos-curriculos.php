<?php 

    add_action( 'admin_menu', 'processos_curriculos_list' );

    function processos_curriculos_list() {
        add_menu_page( 
            'Lista de Currículos', 
            'Lista de Currículos', 
            'manage_options', 
            'processos-curriculos-list-plugin/curriculos-list-page.php', 
            'curriculos_list_page',
            'dashicons-tickets', 5  );
    }

    function curriculos_list_page() {

        $args = array(
            'post_type' => 'processos',
            'post_status' => 'publish',
            'post_per_page' => -1
        );

        $query = new WP_Query( $args );
    
        ?>

        <form>

        </form>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th id="cb" class="manage-column column-cb check-column" scope="col"></th>
                    <th id="columnname" class="manage-column column-columnname" scope="col">Processos</th>
                    <th id="columnname" class="manage-column column-columnname num" scope="col"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="manage-column column-cb check-column" scope="col"></th>
                    <th class="manage-column column-columnname" scope="col"></th>
                    <th class="manage-column column-columnname num" scope="col"></th>
                </tr>
            </tfoot>
            <tbody>
                <tr class="alternate">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname"></td>
                    <td class="column-columnname"></td>
                </tr>
                <tr>
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname"></td>
                    <td class="column-columnname"></td>
                </tr>
                <?php 
                    if($query->have_posts()) {
                        $i = 0;
                        while($query->have_posts()) : $query->the_post(); ?>
                            <tr <?php echo ($i%2 === 0) ? 'class="alternate"': '';?> valign="top">
                                <th class="check-column" scope="row">                       
                                </th>
                                <td class="column-columnname">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

                                    <div class="row-actions">
                                    </div>
                                </td>
                                <td class="column-columnname"></td>
                            </tr>
                            <?php 
                            $i++;
                        endwhile;
                        wp_reset_postdata();
                    }
                ?>
                <tr class="alternate" valign="top">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">
                        <div class="row-actions">
                            <span><a href="#">Action</a> |</span>
                            <span><a href="#">Action</a></span>
                        </div>
                    </td>
                    <td class="column-columnname"></td>
                </tr>
                <tr valign="top">
                    <th class="check-column" scope="row"></th>
                    <td class="column-columnname">
                        <div class="row-actions">
                            <span><a href="#">Action</a> |</span>
                            <span><a href="#">Action</a></span>
                        </div>
                    </td>
                    <td class="column-columnname"></td>
                </tr>
            </tbody>
        </table>

    <?php }
