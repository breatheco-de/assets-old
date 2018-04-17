<?php

/* no-replit.html */
class __TwigTemplate_54b31f7f667bfb1c0e66bdaa3463eaa4d60fdda8286ee42be2d7a4bfe1943122 extends Twig_Template
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
                    <h3 class=\"ml-2\">What are you looking to practice?</h3>
                    <select class=\"form-control\" onChange=\"redirect(event);\">
                        <option value=\"-1\" selected>Select a cohort</option>
                        <?php foreach(\$cohorts as \$key => \$repls){ ?>
                            <option value=\"<?php echo \$key; ?>\"><?php echo \$key; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
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
        return "no-replit.html";
    }

    public function getDebugInfo()
    {
        return array (  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "no-replit.html", "/home/ubuntu/workspace/apps/replit/templates/no-replit.html");
    }
}
