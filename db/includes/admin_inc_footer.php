										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" id="mainfooterarea"></td>
			</tr>
			<tr>
				<td align="center" style="padding-top:4px;">
					
				</td>
			</tr>
		</table>
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