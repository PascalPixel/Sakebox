<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Sakebox
 */
?>

	</div><!-- #content -->

	<footer class="footer" id="colophon" class="site-footer" role="contentinfo">
	  <div class="container">
    	<div class="row">
    	  <div class="col-sm-6">
      	  <!-- Begin MailChimp Signup Form -->
          <form action="http://superpencil.us7.list-manage1.com/subscribe/post?u=43a0823be5d7c532fa4f7f239&amp;id=65b3ce1c66" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
            <div id="mc_embed_signup">
              <div class="row">
                <div class="col-xs-8">
                	<input type="email" value="" name="EMAIL" class="email form-control" id="mce-EMAIL" placeholder="Subscribe to e-mail updates" required="">
                  <div style="position: absolute; left: -5000px;"><input type="text" name="b_43a0823be5d7c532fa4f7f239_65b3ce1c66" value=""></div>
                </div>
                <div class="col-xs-4">
                	<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-success">
                </div>
              </div>
            </div>
          </form>
          <!--End mc_embed_signup-->
    	  </div>
    		<div class="col-sm-6">
    		  <div class="site-info">
      		  © 2014 <a href="http://www.rinkya.com">Rinkya</a>, LLC
      			<br>
      			<?php printf( __( 'Theme: %1$s by %2$s.', 'sakebox' ), 'Sakebox', '<a href="http://superpencil.com/" rel="designer">Superpencil.com</a>' ); ?>
      		</div><!-- .site-info -->
    		</div>
    	</div>
    </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<div class="modal fade" id="subscribe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Begin MailChimp Signup Form -->
      <div id="mc_embed_signup">
        <form action="http://superpencil.us7.list-manage.com/subscribe/post?u=43a0823be5d7c532fa4f7f239&amp;id=65b3ce1c66" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Stay in the loop!</h4>
          </div>
          <div class="modal-body">
            <h2 class="signup-text text-center">Submit your e-mail and we’ll keep you posted when our box launches</h2>
            <p class="text-center">We won't spam you and unsubscribing is easily done from any Sakebox e-mail.</p>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-xs-8">
                <div class="mc-field-group">
                	<input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL" placeholder="Your e-mail">
                </div>
              	<div id="mce-responses" class="clear">
              		<div class="response" id="mce-error-response" style="display:none"></div>
              		<div class="response" id="mce-success-response" style="display:none"></div>
              	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_43a0823be5d7c532fa4f7f239_65b3ce1c66" value=""></div>
              </div>
              <div class="col-xs-4">
              	<input type="submit" value="Submit" name="subscribe" id="mc-embedded-subscribe" class="btn btn-success btn-block">
              </div>
            </div>
          </div>
        </form>
      </div>
      <!--End mc_embed_signup-->
    </div>
  </div>
</div>

<?php wp_footer(); ?>

</body>
</html>
