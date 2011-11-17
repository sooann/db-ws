										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" class="headerdivider"></td>
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
	if (isset($WebpageTrackingID)) {
		$node = new sqlNode();
		$node->table = "webpagetracking";
		$node->push("date","HTMLTRANSFERED",now());
		$node->where = "webtracking_id = " . $WebpageTrackingID;
		$db->update($node);
	}
	
	//Disconnect Data Connection
	$db->disconnect();

?>