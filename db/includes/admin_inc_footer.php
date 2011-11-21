										</section>
                <footer>
                	&copy; Copyright. All Rights Reserved.
                </footer>
            </div>
        </article>
        
	</section>
	<!-- /Main Content -->
	
	</div>
	<!-- /Fixed Layout Wrapper -->

	<!-- JS Libs at the end for faster loading -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="../js/jquery/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
	<script src="../js/libs/selectivizr.js"></script>
	<script src="../js/jquery/jquery.tipsy.js"></script>
	<script src="../js/jquery/excanvas.js"></script>
    <script type="text/javascript" src="../js/jquery/jquery.dd.js"></script>
	<script src="../js/script.js"></script>
    
</body>
</html>
<?php 

	//Log Web Load Time
	if (isset($intWebpageTrackingID)) {
		$node = new sqlNode();
		$node->table = "webpagetracking";
		$node->push("date","HTMLTRANSFERED",now());
		$node->where = "webtracking_id = " . $intWebpageTrackingID;
		$db->update($node);
	}
	
	//Disconnect Data Connection
	$db->disconnect();

?>