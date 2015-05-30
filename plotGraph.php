<?php require dirname(__FILE__).'/header.php'?>
<?php require dirname(__FILE__).'/MySQL/mysql.php';?>
	
		<div class="inputs">
			<form id="submitForm" action="">
				<table class="dashBoardTable">
					<tr>
						<td class="text">
							Return to previous home
						</td>
						<td>
								<button><a href="/">Home</a></button>
						</td>
					</tr>
					<tr style="border-top: #ccc 2px solid;">
						<td class="text">Information category</td>
						<td>
							<select id="infoScope">
								<option value="">---Information---</option>
								<?php
									$result = sqlQuery("SELECT topic_table FROM topics");									
									for ($x = 0; $x < countNumOfRows($result); $x++) {
										$fetch = fetchArray($result);
							      echo '<option value="'.$fetch['topic_table'].'">'.$fetch['topic_table'].'</option>';
							    }
								?>															
							</select>
						</td>
					</tr>	
					<tr>
						<td class="text">Choose influencer</td>
						<td>
							<select id="nodeList">
								<option value="">---Influencer---</option>
								<?php
									$result = sqlQuery("SELECT user_id FROM population");									
									for ($x = 0; $x < countNumOfRows($result); $x++) {
										$fetch = fetchArray($result);
							      echo '<option value="'.$fetch['user_id'].'">'.$fetch['user_id'].'</option>';
							    }
								?>
							</select>
						</td>
					</tr>						
					<tr style="border-top: #ccc 1px dashed;">
						<td class="text">Include randomness</td>
						<td>
							<div id="random" isRandom="0">
						    <input type="radio" id="random1" name="random" value="1"><label for="random1">Yes</label>
						    <input type="radio" id="random2" name="random" value="0" checked="checked"><label for="random2">No</label>
						  </div>
						</td>
					</tr>					
					<tr>
						<td class="text">Spectrum selector</td>
						<td>
							<div id="slider-range-min"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="spectrum" starterParameter="1"></div>
						</td>
					</tr>
			
				
					<tr>
						<td></td>
						<td>
							<div id="submitButton" type="submit" onclick="changeNode()">Submit</div>
						</td>
					</tr>
					<tr style="border-top: #ccc 1px dashed;">
						<td class="text">Set Top K influencers</td>
						<td>
							<div id="setTopK" onclick="">Set Top K</div>							
						</td>
					</tr>						
					<tr>
						<td colspan="2" style="border-top: #ccc 2px solid;" class="text">Results parameters</td>
					</tr>
					<tr>
						<td class="text">Data Base</td>
						<td>
							<span id="dataBase"> - </span>
						</td>						
					</tr>
					<tr>
						<td class="text">Chosen Topic</td>
						<td>
							<span id="dataTopic"> - </span>
						</td>						
					</tr>					
					<tr>
						<td class="text">Total active nodes</td>
						<td>
							<div id="activeNode">0</div>
						</td>
					</tr>
					<tr>
						<td class="text">Maximum Connections</td>
						<td>
							<div id="connectionNo">0</div>
						</td>
					</tr>					
					<tr>
						<td class="text">View Step</td>
						<td>
							<div id="stepShower">0</div>
						</td>
					</tr>
					<tr>
						<td class="text">Show connections</td>
						<td>
						  <div id="connection" showConnection="false">
						    <input type="radio" id="showConnection1" name="showConnection" value="yes"><label for="showConnection1">Yes</label>
						    <input type="radio" id="showConnection2" name="showConnection" value="no" checked="checked"><label for="showConnection2">No</label>
						  </div>
						</td>
					</tr>									
				</table>
			</form>
		</div>
		<div id="graphsResults">
		  <ul>
		    <li><a href="#graph-1">Map</a></li>
		    <li><a href="#graph-2">Receiver by step</a></li>
		    <li><a href="#graph-3">View without position</a></li>
		  </ul>
		  <div id="graph-1" class="graph">
			  <div id="progressbar"></div>
				<div id="slider-range"></div>
		  </div>
		  <div id="graph-2">
			  <div id="stepBarChart"></div>	
		  </div>
		  <div id="graph-3">
			  <div id="bubbleChart" style="height:500px;"></div>	
		  </div>		  
		</div>
		
		<div id="setKDialog" title="Find Top K nodes">
		  <p>Please input the number of the nodes :&nbsp;&nbsp;<input id="kNumber" type="text" name="k" value=""></p>
		  <div id="topKSubmit" onclick="setTopK()"> Find Top K nodes</div>
		  <div id="topKResult"></div>
		  <input id="topKNodeArrayInput" type="hidden" value="" name="topKNodeArray">
		</div>

<?php require dirname(__FILE__).'/footer.php'?>