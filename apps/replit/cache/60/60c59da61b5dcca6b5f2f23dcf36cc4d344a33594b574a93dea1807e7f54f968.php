<?php

/* msg.html */
class __TwigTemplate_28437be7d0f2bffebcd9ce300a825324dd8adb51052d13a0b71420fb36172259 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <title>Replit Selector</title>
        <link rel=\"stylesheet\" href=\"bootstrap4.min.css\" type=\"text/css\" />
    </head>
    <body>
        <div class=\"container mt-5\">
            <div class=\"row\">
                <div class=\"col-12\">
                    <img class=\"float-left mr-2\" src=\"/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32\"></img>
                    <h3 class=\"ml-2\">We could not find an exercise, please select your cohort:</h3>
                </div>
            </div>
            <select class=\"form-control\" onChange=\"redirect(event);\">
                <option value=\"-1\" selected>Select a cohort</option>
                <?php foreach(\$cohorts as \$key => \$repls){ ?>
                    <option value=\"<?php echo \$key; ?>\"><?php echo \$key; ?></option>
                <?php } ?>
            </select>
        </div>
        <script type=\"text/javascript\">
            function redirect(e){ 
                if(e.target.value != -1) 
                    location.href = '/apps/replit/?r=<?php echo \$_GET['r']; ?>&c='+e.target.value; 
            }
        </script>
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "msg.html";
    }

    public function getDebugInfo()
    {
        return array (  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "msg.html", "/home/ubuntu/workspace/apps/replit/templates/msg.html");
    }
}
