function barPlot(){
	
	var margin = {top:20,right:20,bottom:20,left:40}, 
		width = 300-margin.left-margin.right, 
		height = 150-margin.top-margin.bottom, 
		x = d3.scaleBand().rangeRound([0,width]).padding(0.1), 
		y = d3.scaleLinear().rangeRound([height,0]); 
		
		function chart(selection){ 
			selection.each(function(data){ 
				
				data.forEach(function(d){ d.counts=+d.counts; }); 
			
				x.domain(["1","2","3","4","5","6","7","8","9","10"]); 
				y.domain([0, d3.max(data,function(d){return d.counts;})]); 
				
				var svg = d3.select(this)
										.selectAll("svg")
										.data([data]);
				var g = svg.append("g")
									.attr("transform", 
												"translate(" + margin.left + "," + margin.top+")");
									
				var color = ["#1a9850", "#1a9850", "#1a9850", "#ffd700", "#ffd700", 
										"#ffd700", "#d73027", "#d73027", "#d73027", "#f1b6da"];
										
				for(var item = 0, nItems = data.length - 1; item < nItems; item++){
					if(data[item].response===data[nItems].response){
						color[data[nItems].response-1]="#386cb0"; }} 
						
				g.append("g")
					.attr("class","axisaxis--x")
					.attr("transform","translate(0,"+height+")")
					.call(d3.axisBottom(x)); 
				
				var nTicks = 4; 
				
				g.append("g")
					.attr("class","axisaxis--y")
					.call(d3.axisLeft(y)
					.ticks(nTicks)); 
				
				svg.append("text")
					.attr("transform","rotate(-90)")
					.attr("y",0)
					.attr("x",-30)
					.attr("dy","0.71em")
					.attr("text-anchor","end")
					.attr("font-size","11px")
					.text("Counts"); 
					
				g.selectAll(".bar")
					.data(data)
					.enter()
					.append("rect")
					.attr("x",function(d){return x(d.response);})
					.attr("y",function(d){return y(d.counts);})
					.attr("width",x.bandwidth())
					.attr("fill",function(d,i){return color[d.response-1];})
					.attr("height",function(d){return height-y(d.counts);});}	
			) // END: selection each
		} ; // END: function chart
	return chart;}; // END: function braplot
