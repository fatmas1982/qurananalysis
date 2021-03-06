<?php 
#   PLEASE DO NOT REMOVE OR CHANGE THIS COPYRIGHT BLOCK
#   ====================================================================
#
#    Quran Analysis (www.qurananalysis.com). Full Semantic Search and Intelligence System for the Quran.
#    Copyright (C) 2015  Karim Ouda
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#    You can use Quran Analysis code, framework or corpora in your website
#	 or application (commercial/non-commercial) provided that you link
#    back to www.qurananalysis.com and sufficient credits are given.
#
#  ====================================================================
require_once("../global.settings.php");

$lang = "AR";



if ( isset($_GET['lang']) )
{
	$lang = $_GET['lang'];
}

loadModels("core",$lang);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Full text of the Quran | Quran Analysis </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Full text of the Quran in one page">
    <meta name="author" content="">

	<script type="text/javascript" src="<?=$JQUERY_PATH?>" ></script>
	<script type="text/javascript" src="<?=$MAIN_JS_PATH?>"></script>
	<link rel="stylesheet" href="/qe.style.css?bv=<?=$BUILD_VERSION?>" />
	<link rel="icon" type="image/png" href="/favicon.png">
	<script type="text/javascript">
	</script>
     
       
  </head>
  <body>
			<?php 
				require("./analysis.template.start.code.php");
		
		?>	
				
  <div id='main-container'>
	
	    <?php include_once("help-content.php"); ?>
		
			  	<div >
			  	
			  <!-- 	<div id='fqt-search-area'>
					<span ><b>Search For</b></span>
					<input id='filterSearch' name='filterSearch' type="text"  value='' onkeyup="filterConversation()"   ></input>
					<span id='result-count' ></span>
				</div>
				 -->
	
		  		 <fieldset id="quran-full-text-fs">
		  		 
  				    <legend>Quran Text</legend>
			  		
						<table class='analysis-table' >
			  			<thead>
			  				<tr>
			  					<th>
				  					General Index
				  				</th>
				  				<th>
				  					Chapter Index
				  				</th>
				  				<th>
				  					Chapter Name
				  				</th>
				  				<th>
				  					Verse #
				  				</th>
				  				<th>
				  					Text
				  				</th>
				  			</tr>
			  			</thead>
			  			<tbody>
							<?php 
						
							
							$QURAN_TEXT = getModelEntryFromMemory($lang, "MODEL_CORE", "QURAN_TEXT", "");
							
							$META_DATA = getModelEntryFromMemory($lang, "MODEL_CORE", "META_DATA", "");
								
		
							
							$i=0;
							/* SURA'S LOOP **/
					  		for ($s=0;$s<$numberOfSuras;$s++)
					  		{
					  		
					  				
					  			$suraSize = count($QURAN_TEXT[$s]);
					  		
					  			/* VERSES LOOP **/
						  		for ($a=0;$a<$suraSize;$a++)
						  		{
						  			$i++;
						  			$verseText = $QURAN_TEXT[$s][$a];
						  			$suraName = $META_DATA['SURAS'][$s]['name_'.strtolower($lang)];
						  			
									
									
							?>
			  					<tr id='verse-<?=$i?>' class='fqt-verse'>
			  						<td><?=$i?></td>
			  						<td><?=$s+1?></td>
				  					<td><?=$suraName?></td>
				  					<td><?=$a+1?></td>
				  					<td><span textfor='verse-<?=$i?>'><?=$verseText?></span></td>
				  	
				  				</tr>			  				
			  				<?php 
								}
					  		}
			  				?>
		  	
			  			</tbody>
			  			
			  		</table>
			  		
			  </fieldset>
			  	
			  	</div>	
   </div>
   
		<?php 
				require("./analysis.template.end.code.php");
		
		?>	
	<script type="text/javascript">

		var lastSearchTimeout = null;
				
		$(document).ready(function()
		{


		
		});


		

		function filterConversation()
		{
			if ( lastSearchTimeout!=null)
			{
				clearTimeout(lastSearchTimeout);
				lastSearchTimeout = null;
			}

			$("#result-count").html("Searching...");
			
			lastSearchTimeout = setTimeout(function()
			{
				
			
				var topic = $("#filterSearch").val();
	
				if ( topic.length==0 )
				{
					//return;
					//removed ret to remove marked from last char
					$("#result-count").html("0 Found");

					$("SPAN[isMarked=1] ").each(function()
							{
								var textSpanObj = $(this);
								
								var conversationString = textSpanObj.html();
								conversationString = conversationString.replace(/\<marked\>/g,"");
						 		conversationString = conversationString.replace(/\<\/marked\>/g,"");

						 		$(this).attr("isMarked","0");

						 		textSpanObj.html(conversationString);
							});
					return;
					
				}
					
				
				
	
				//topic = topic.replace(/(\r\n|\n|\r)/gm,"").toLowerCase();
				var resultCount = 0;
				
				$("TR[class="+'fqt-verse'+"] span ").each(function(topic)
				{
					var textSpanObj = $(this);
	
					var conversationString = textSpanObj.html();
	
			
					
				
					if ( conversationString==null){return;}
					
	
					
	
					if ( $(this).attr("isMarked")==1 )
					{
				 		// used /g since there will be multiple  <b><b>
				 		conversationString = conversationString.replace(/\<marked\>/g,"");
				 		conversationString = conversationString.replace(/\<\/marked\>/g,"");
	
					}
					
	
					topicWithoutRegExp = topic.replace(/([.*+?^=!:${}()|\[\]\/\\])/gm,"");

					//alert(topicWithoutRegExp+" "+topic);
					
					if ( conversationString.match(new RegExp((topic), 'g'))  )
					{

						conversationString = conversationString.replace(new RegExp((topic), 'g'), "<marked>"+topicWithoutRegExp+"</marked>");
						 
					
			 			$(this).attr("isMarked","1");
	
			 			//alert(conversationString);
					
			 			textSpanObj.html(conversationString);
			 			//$("#"+$(this).attr("textfor")).css("display","block");
			 			document.getElementById($(this).attr("textfor")).style.display = "table-row";
	
			 			
					}
					else
					{
						
						textSpanObj.html(conversationString);
						$(this).attr("isMarked","0");
						
						//$("#"+$(this).attr("textfor")).css("display","none");
						document.getElementById($(this).attr("textfor")).style.display = "none";
						
				 			
					}
				
					
						
					
				},new Array(topic));
	
	
				
	
				setTimeout(function(){$("#result-count").html($("marked").length+" Found");},2000);
				setTimeout(function(){$("#result-count").html($("marked").length+" Found");},5000);

				clearTimeout(lastSearchTimeout);
				lastSearchTimeout = null;
				
			},1000);

			
		}	
		
	</script>
		

	<?php 
		require("../footer.php");
	?>
	


  </body>
</html>







