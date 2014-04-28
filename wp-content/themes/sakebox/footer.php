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
         	<div id="footer-sidebar1">
          <?php
          if(is_active_sidebar('footer-sidebar-1')){
          dynamic_sidebar('footer-sidebar-1');
          }
          ?>
          </div>
          <br>
    	    <h4>Sign up for our newsletter</h4>
      	  <!-- Begin MailChimp Signup Form -->
          <form action="http://superpencil.us7.list-manage1.com/subscribe/post?u=43a0823be5d7c532fa4f7f239&amp;id=65b3ce1c66" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
            <div id="mc_embed_signup" class="row">
              <div class="col-xs-8">
              	<input type="email" value="" name="EMAIL" class="email form-control col-sm-8" id="mce-EMAIL" placeholder="Subscribe to e-mail updates" required="">
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_43a0823be5d7c532fa4f7f239_65b3ce1c66" value=""></div>
              </div>
              <div class="col-xs-4">
              	<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-success btn-block">
              </div>
            </div>
          </form>
          <!--End mc_embed_signup-->
    		  <div class="site-info">
      		  Copyright © 2014 <a href="http://www.rinkya.com">Rinkya</a>, LLC &nbsp;&nbsp;|&nbsp;&nbsp; Designed by <a class="superpencil" href="http://superpencil.com/?utm_source=sakebox&amp;amp;utm_medium=sakebox&amp;amp;utm_campaign=sakebox"><img alt="Superpencil Design" src="<?php bloginfo('template_directory'); ?>/img/superpencil-tag-logo.png"> Superpencil</a>
      		</div><!-- .site-info -->
    		</div>
    	  <div class="col-sm-6 hidden-xs">
    		  <div id="footer-sidebar2">
          <?php
          if(is_active_sidebar('footer-sidebar-2')){
          dynamic_sidebar('footer-sidebar-2');
          }
          ?>
          </div>
          <br>
    		  <h4>Latest <a href="/the-sake-bomb">The Saké Bomb</a> post</h4>
          <?php
          	$args = array( 'posts_per_page' => 1 );
          	$loop = new WP_Query( $args );
          	while ( $loop->have_posts() ) : $loop->the_post();
          ?>
            <article <?php post_class(); ?>>
              <?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] != 'content' ) { woo_image( 'width=' . $woo_options['woo_thumb_w'] . '&height=' . $woo_options['woo_thumb_h'] . '&class=thumbnail ' . $woo_options['woo_thumb_align'] ); } ?>
              <header>
              	<h1><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
              </header>
              <section class="entry">
                <?php if ( isset( $woo_options['woo_post_content'] ) && $woo_options['woo_post_content'] == 'content' ) { the_content( __( 'Continue Reading &rarr;', 'woothemes' ) ); } else { the_excerpt(); } ?>
              </section>
            </article><!-- /.post -->    
          <?php endwhile; ?>
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

<!-- begin olark code -->
<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
f[z]=function(){
(a.s=a.s||[]).push(arguments)};var a=f[z]._={
},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
0:+new Date};a.P=function(u){
a.p[u]=new Date-a.p[0]};function s(){
a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
b.contentWindow[g].open()}catch(w){
c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('2215-497-10-2782');/*]]>*/</script><noscript><a href="https://www.olark.com/site/2215-497-10-2782/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
<!-- end olark code -->

<?php wp_footer(); ?>

</body>
</html>
