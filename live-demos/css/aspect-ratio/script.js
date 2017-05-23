    var init = function (settings) {
        var widthInput,heightInput,exampleRatioSelector;
        
        function init() {
            window.removeEventListener('load', init);
            widthInput = document.querySelector('#pixel_width');
            heightInput = document.querySelector('#pixel_height');
            exampleRatioSelector = document.querySelector('#example-ratios');
            theform = document.querySelector('#aspectratio');
            calculateRatio();
            
            document.querySelector('#pixel_width').addEventListener('change',function(){
                calculateRatio();
                document.querySelector('#example-child').style.display = "block";
            });
            
            document.querySelector('#pixel_height').addEventListener('change',function(){
                calculateRatio();
                document.querySelector('#example-child').style.display = "block";
            });
            
            document.querySelector('#image-url').addEventListener('change',getImage);
            
            
            
            if(!settings['ratio']) loadSelector();
            else{
                exampleRatioSelector.style.display = "none";
                
                for(var i = 0; i<examples.length;i++)
                {
                    if(examples[i].value == settings['ratio'])
                    {
                        document.querySelector('#example-container').style.width = examples[i].width+'px';
                        document.querySelector('#example-container').style.height = examples[i].height+'px';
                    }
                }
            }
        }
        
        function loadSelector()
        {
             for (var i=0; i < examples.length; i++) {
                var opt = document.createElement('option');
                opt.value = examples[i].value;
                //opt.innerHTML = examples[i].width.toString()+' x '+examples[i].height.toString()+' '+examples[i].name;
                opt.innerHTML = examples[i].name;
                exampleRatioSelector.appendChild(opt);
             }
             
             exampleRatioSelector.addEventListener('change',resizeExampleContainer);
        }
        
        function resizeExampleContainer(){
            
            document.querySelector('#example-container').style.width = examples[exampleRatioSelector.selectedIndex-1].width + "px";
            document.querySelector('#example-container').style.height = examples[exampleRatioSelector.selectedIndex-1].height + "px";
            
        }
        
        function gcd(width, height)
        {
            if (width == 0 || height == 0)
                return Math.abs( Math.max(Math.abs(width), Math.abs(height)) );
        
            var ratio = width % height;
            return (ratio != 0) ?
                gcd(height, ratio) :
                Math.abs(height);
        }
        
        function getImage(event){
            //https://i43i0mkghf-flywheel.netdna-ssl.com/wp-content/uploads/2015/07/CommonSenseService02.jpg
            var img = new Image();
            img.onload = function(){
                heightInput.value = img.height;
                widthInput.value = img.width;
                
                event.target.parentElement.classList.add('has-success');
                event.target.parentElement.classList.remove('has-error');
                event.target.parentElement.classList.add('has-feedback');
                var children = event.target.parentElement.childNodes;
                for (var i=0; i < children.length; i++) {
                    if (children[i].classList && children[i].classList.contains("glyphicon")) {
                        children[i].classList.remove('glyphicon-remove');
                        children[i].classList.remove('hidden');
                        children[i].classList.add('glyphicon-ok');
                        break;
                    }
                }
                
                document.querySelector('#example-child').style.display = "none";
              // code here to use the dimensions
            }
            img.onerror = function(){
                event.target.parentElement.classList.add('has-error');
                event.target.parentElement.classList.remove('has-success');
                event.target.parentElement.classList.add('has-feedback');
                var children = event.target.parentElement.childNodes;
                for (var i=0; i < children.length; i++) {
                    if (children[i].classList && children[i].classList.contains("glyphicon")) {
                        children[i].classList.remove('glyphicon-ok');
                        children[i].classList.add('glyphicon-remove');
                        children[i].classList.remove('hidden');
                        break;
                    }
                }
            }
            img.src = event.target.value;
            document.querySelector('#example-container').style.backgroundImage = "url('"+event.target.value+"')";
            document.querySelector('#example-child').style.display = "block";
        }
        
        function calculateRatio()
        {
            var widthValue = widthInput.value;
            var heightValue = heightInput.value;
            var result = gcd(widthValue,heightValue);
            
            document.querySelector('#ratio_width').value = widthValue/result;
            document.querySelector('#ratio_height').value = heightValue/result;
            
            document.querySelector('#example-child').style.height = heightValue+"px";
            document.querySelector('#example-child').style.width = widthValue+"px";
        }
        window.addEventListener('load', init, false);
        
        var examples = [
            { value: "16x9", name: "16:9", width: 256, height: 144},
            { value: "16x5", name: "16:5", width: 400, height: 125},
            { value: "4x3", name: "4:3 (VGA)", width: 640, height: 480},
            { value: "8x5", name: "8:5 (Wide screen monitor)", width: 200, height: 125},
            { value: "8x3", name: "8:3", width: 400, height: 150},
            { value: "32x27", name: "32:27 (NTSC)", width: 576, height: 486},
            { value: "2x3", name: "2:3 (HVGA)", width: 320, height: 480}
            ];
    };