<?php global $TLPportfolio; ?>
<div class="wrap">
    <div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br/></div>
    <h2><?php _e( 'Shortcode generator', 'tlp-portfolio' ); ?></h2>
    <div class="tlp-content-holder">
        <div class="tch-left">
            <div class="postbox" id="scg-wrapper">
                <h3 style="padding: 10px" class="hndle ui-sortable-handle"><span>Shortcode</span></h3>
                <div class="inside">
                    <h4>Layout and filter</h4>
                    <div class="scg-wrapper">
                        <div class="scg-row">
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Layout</label></div>
                                <div class="scg-field">
                                    <select name="layout">
										<?php
										$layouts = $TLPportfolio->scLayouts();
										foreach ( $layouts as $key => $layout ) {
											echo "<option value={$key}>$layout</option>";
										}
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Column</label></div>
                                <div class="scg-field">
                                    <select name="col">
                                        <option value="">Default</option>
										<?php
										$cols = $TLPportfolio->scColumns();
										foreach ( $cols as $key => $col ) {
											echo "<option value={$key}>$col</option>";
										}
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Order by</label></div>
                                <div class="scg-field">
                                    <select name="orderby">
                                        <option value="">Default</option>
										<?php
										$obs = $TLPportfolio->scOrderBy();
										foreach ( $obs as $key => $ob ) {
											echo "<option value={$key}>$ob</option>";
										}
										?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="scg-row">
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Order</label></div>
                                <div class="scg-field">
                                    <select name="order">
                                        <option value="">Default</option>
										<?php
										$orders = $TLPportfolio->scOrder();
										foreach ( $orders as $key => $order ) {
											echo "<option value={$key}>$order</option>";
										}
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Total Number</label></div>
                                <div class="scg-field"><input type="number" name="number"/>
                                    <p class="description">Leave it blank to display all. (Only number is allowed)</p>
                                </div>
                            </div>
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Category</label></div>
                                <div class="scg-field">
									<?php
									$cats = $TLPportfolio->getAllPortFolioCategoryList();
									if ( ! empty( $cats ) ) {

										foreach ( $cats as $id => $cat ) {
											echo "<div class='checkbox-item'><input type='checkbox' id='cat_id{$id}' name='cat[]' value='{$id}'><label for='cat_id{$id}'>{$cat}</label></div>";
										}
									}
									?>
                                    <p class="description">Leave it blank to display all.</p>
                                </div>
                            </div>
                        </div>
                        <div class="scg-row">
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Disable Image</label></div>
                                <div class="scg-field">
                                    <label for="image-false"><input id="image-false" value="false" type="checkbox"
                                                                    name="image"> Disable</label>
                                </div>
                            </div>
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Short description limit</label></div>
                                <div class="scg-field"><input type="number" name="letter-limit"/>
                                    <p class="description">Leave it blank to default 100 letter</p>
                                </div>
                            </div>
                            <div class="scg-item-wrap">
                                <div class="scg-label"><label>Wrapper Class</label></div>
                                <div class="scg-field"><input type="text" name="class"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Style</h4>
                    <div class="scg-wrapper">
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Title color</label></div>
                            <div class="scg-field"><input type="text" class="tlp-color" name="title-color"></div>
                        </div>
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Title font size</label></div>
                            <div class="scg-field">
                                <select name="title-font-size">
                                    <option value="">Default</option>
									<?php
									foreach ( $TLPportfolio->scFontSize() as $key => $size ) {
										echo "<option value='{$key}'>{$size}</option>";
									}
									?>
                                </select>
                            </div>
                        </div>
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Title font weight</label></div>
                            <div class="scg-field">
                                <select name="title-font-weight">
                                    <option value="">Default</option>
									<?php
									foreach ( $TLPportfolio->scTextWeight() as $key => $weight ) {
										echo "<option value='{$key}'>{$weight}</option>";
									}
									?>
                                </select>
                            </div>
                        </div>
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Title alignment</label></div>
                            <div class="scg-field">
                                <select name="title-alignment">
                                    <option value="">Default</option>
									<?php
									foreach ( $TLPportfolio->scAlignment() as $key => $alignment ) {
										echo "<option value='{$key}'>{$alignment}</option>";
									}
									?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="scg-wrapper">
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Short description color</label></div>
                            <div class="scg-field"><input type="text" class="tlp-color" name="short-desc-color"></div>
                        </div>
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Short description font size</label></div>
                            <div class="scg-field">
                                <select name="short-desc-font-size">
                                    <option value="">Default</option>
                                    <?php
                                    foreach ( $TLPportfolio->scFontSize() as $key => $size ) {
                                        echo "<option value='{$key}'>{$size}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Short description font weight</label></div>
                            <div class="scg-field">
                                <select name="short-desc-font-weight">
                                    <option value="">Default</option>
                                    <?php
                                    foreach ( $TLPportfolio->scTextWeight() as $key => $weight ) {
                                        echo "<option value='{$key}'>{$weight}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="scg-item-wrap scg-col-4">
                            <div class="scg-label"><label>Short description alignment</label></div>
                            <div class="scg-field">
                                <select name="short-desc-alignment">
                                    <option value="">Default</option>
                                    <?php
                                    foreach ( $TLPportfolio->scAlignment() as $key => $alignment ) {
                                        echo "<option value='{$key}'>{$alignment}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="sc-output">
                        <textarea></textarea>
                        <p class="description">Click to copy the shortcode.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tch-right">
            <div id="pro-feature" class="postbox">
                <div class="handlediv" title="Click to toggle"><br></div>
                <h3 class="hndle ui-sortable-handle"><span>TLP Portfolio Pro</span></h3>
                <div class="inside">
					<?php echo $TLPportfolio->proFeatureList(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="tlp-help">
        <p style="font-weight: bold"><?php _e( 'Short Code', 'tlp-portfolio' ); ?> :</p>
        <code>[tlpportfolio col="2" number="4" cat="5,78" orderby="title" order="ASC" layout="1"]</code><br>
        <p><?php _e( 'col = The number of column you want to create (1,2,3,4)', 'tlp-portfolio' ); ?></p>
        <p><?php _e( 'number = The number of the portfolio, you want to display', 'tlp-portfolio' ); ?></p>
        <p><?php _e( 'orderby = Orderby (title , date, menu_order)', 'tlp-portfolio' ); ?></p>
        <p><?php _e( 'layout = Layout (1,2,3,isotope)', 'tlp-portfolio' ); ?></p>
        <p><?php _e( 'cat = Category id', 'tlp-portfolio' ); ?></p>
        <p><?php _e( 'order = ASC, DESC', 'tlp-portfolio' ); ?></p>
        <p class="tlp-help-link"><a class="button-primary"
                                    href="http://demo.radiustheme.com/wordpress/plugins/tlp-portfolio/"
                                    target="_blank"><?php _e( 'Demo', 'tlp-portfolio' ); ?></a> <a
                    class="button-primary"
                    href="https://radiustheme.com/how-to-setup-and-configure-tlp-portfolio-free-version-for-wordpress/"
                    target="_blank"><?php _e( 'Documentation', 'tlp-portfolio' ); ?></a></p>
    </div>
</div>
