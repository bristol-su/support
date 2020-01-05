<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* /package.html.twig */
class __TwigTemplate_b40a87d4c113492937a644d7e3e1d861d40f31635b83e2f1eada35e290beff7e extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout.html.twig", "/package.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 90
    public function block_title($context, array $blocks = [])
    {
        // line 91
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? null), "title", []), "html", null, true);
        echo " &raquo; ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "FullyQualifiedStructuralElementName", []), "html", null, true);
        echo "
";
    }

    // line 94
    public function block_content($context, array $blocks = [])
    {
        // line 95
        echo "    ";
        $context["self"] = $this;
        // line 96
        echo "
    <div class=\"row\">

        <div class=\"span4\">
            <div class=\"btn-group view pull-right\" data-toggle=\"buttons-radio\">
                <button class=\"btn details\" title=\"Show descriptions and method names\">
                    <i class=\"icon-list\"></i></button><button class=\"btn simple\" title=\"Show only method names\">
                    <i class=\"icon-align-justify\"></i>
                </button>
            </div>
            <ul class=\"side-nav nav nav-list\">
                <li class=\"nav-header\">
                    <i class=\"icon-map-marker\"></i> Packages
                </li>
                <a href=\"";
        // line 110
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["node"] ?? null)]), "html", null, true);
        echo "\" title=\"";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "\">
                    <i class=\"icon-th\"></i> ";
        // line 111
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "
                </a>
                <ul class=\"nav nav-list nav-packages\">
                    ";
        // line 114
        echo $context["self"]->getrenderPackageSidebar(($context["node"] ?? null));
        echo "
                </ul>
            </ul>
        </div>

        <div class=\"span8 package-contents\">
            ";
        // line 120
        echo $context["self"]->getrenderPackageDetails(($context["node"] ?? null));
        echo "
        </div>
    </div>
";
    }

    // line 3
    public function getelementSummary($__element__ = null, $__type__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "element" => $__element__,
            "type" => $__type__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 4
            echo "    <div id=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            echo "\" class=\"element ajax clickable ";
            echo twig_escape_filter($this->env, ($context["type"] ?? null), "html", null, true);
            echo "\">
        <h1>";
            // line 5
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            echo "<a href=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)]), "html", null, true);
            echo "\">¶</a></h1>
        <p class=\"short_description\">";
            // line 6
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "summary", []), "html", null, true);
            echo "</p>
        <div class=\"details collapse\">";
            // line 7
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "description", []), "html", null, true);
            echo "</div>
        <a href=\"";
            // line 8
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)]), "html", null, true);
            echo "\" class=\"more\">« More »</a>
    </div>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 12
    public function getbuildBreadcrumb($__element__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "element" => $__element__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 13
            echo "    ";
            $context["self"] = $this;
            // line 14
            echo "
    ";
            // line 15
            if (($this->getAttribute(($context["element"] ?? null), "parent", []) && ($this->getAttribute($this->getAttribute(($context["element"] ?? null), "parent", []), "name", []) != "\\"))) {
                // line 16
                echo "        ";
                echo $context["self"]->getbuildBreadcrumb($this->getAttribute(($context["element"] ?? null), "parent", []));
                echo "
    ";
            }
            // line 18
            echo "
    <li>
        <span class=\"divider\">\\</span><a href=\"";
            // line 20
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["element"] ?? null)]), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["element"] ?? null), "name", []), "html", null, true);
            echo "</a>
    </li>
";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 24
    public function getrenderPackageDetails($__node__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "node" => $__node__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 25
            echo "    ";
            $context["self"] = $this;
            // line 26
            echo "
    <ul class=\"breadcrumb\">
        <li><a href=\"";
            // line 28
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["index.html"]), "html", null, true);
            echo "\"><i class=\"icon-th\"></i></a></li>
        ";
            // line 29
            echo $context["self"]->getbuildBreadcrumb(($context["node"] ?? null));
            echo "
    </ul>

    ";
            // line 32
            if ((((((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "classes", [])) > 0) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "interfaces", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "traits", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "functions", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "constants", [])) > 0))) {
                // line 33
                echo "
        ";
                // line 34
                if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "functions", [])) > 0)) {
                    // line 35
                    echo "            <div class=\"package-indent\">
                <h3><i class=\"icon-custom icon-function\"></i> Functions</h3>
                ";
                    // line 37
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "functions", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["function"]) {
                        // line 38
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["function"], "function");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['function'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 40
                    echo "            </div>
        ";
                }
                // line 42
                echo "
        ";
                // line 43
                if ((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "constants", [])) > 0)) {
                    // line 44
                    echo "            <div class=\"package-indent\">
                <h3><i class=\"icon-custom icon-constant\"></i> Constants</h3>
                ";
                    // line 46
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "constants", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
                        // line 47
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["constant"], "constant");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 49
                    echo "            </div>
        ";
                }
                // line 51
                echo "
        ";
                // line 52
                if ((((twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "classes", [])) > 0) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "interfaces", [])) > 0)) || (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "traits", [])) > 0))) {
                    // line 53
                    echo "            <div class=\"package-indent\">
                <h3><i class=\"icon-custom icon-class\"></i> Classes, interfaces and traits</h3>
                ";
                    // line 55
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "traits", [])]));
                    foreach ($context['_seq'] as $context["_key"] => $context["trait"]) {
                        // line 56
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["trait"], "trait");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['trait'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 58
                    echo "
                ";
                    // line 59
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "interfaces", [])]));
                    foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                        // line 60
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["interface"], "interface");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 62
                    echo "
                ";
                    // line 63
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "classes", [])]));
                    foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                        // line 64
                        echo "                    ";
                        echo $context["self"]->getelementSummary($context["class"], "class");
                        echo "
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 66
                    echo "            </div>
        ";
                }
                // line 68
                echo "    ";
            }
            // line 69
            echo "
    ";
            // line 70
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "children", []));
            foreach ($context['_seq'] as $context["_key"] => $context["package"]) {
                // line 71
                echo "        ";
                echo $context["self"]->getrenderPackageDetails($context["package"]);
                echo "
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['package'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 75
    public function getrenderPackageSidebar($__node__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "node" => $__node__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 76
            echo "    ";
            $context["self"] = $this;
            // line 77
            echo "
    ";
            // line 78
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute(($context["node"] ?? null), "children", [])]));
            foreach ($context['_seq'] as $context["_key"] => $context["package"]) {
                // line 79
                echo "    <li>
        <a href=\"";
                // line 80
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [$context["package"]]), "html", null, true);
                echo "\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["package"], "name", []), "html", null, true);
                echo "\">
            <i class=\"icon-th\"></i> ";
                // line 81
                echo twig_escape_filter($this->env, $this->getAttribute($context["package"], "name", []), "html", null, true);
                echo "
        </a>
        <ul class=\"nav nav-list nav-packages\">
            ";
                // line 84
                echo $context["self"]->getrenderPackageSidebar($context["package"]);
                echo "
        </ul>
    </li>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['package'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "/package.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  415 => 84,  409 => 81,  403 => 80,  400 => 79,  396 => 78,  393 => 77,  390 => 76,  378 => 75,  356 => 71,  352 => 70,  349 => 69,  346 => 68,  342 => 66,  333 => 64,  329 => 63,  326 => 62,  317 => 60,  313 => 59,  310 => 58,  301 => 56,  297 => 55,  293 => 53,  291 => 52,  288 => 51,  284 => 49,  275 => 47,  271 => 46,  267 => 44,  265 => 43,  262 => 42,  258 => 40,  249 => 38,  245 => 37,  241 => 35,  239 => 34,  236 => 33,  234 => 32,  228 => 29,  224 => 28,  220 => 26,  217 => 25,  205 => 24,  185 => 20,  181 => 18,  175 => 16,  173 => 15,  170 => 14,  167 => 13,  155 => 12,  137 => 8,  133 => 7,  129 => 6,  123 => 5,  116 => 4,  103 => 3,  95 => 120,  86 => 114,  80 => 111,  74 => 110,  58 => 96,  55 => 95,  52 => 94,  43 => 91,  40 => 90,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/package.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig//package.html.twig");
    }
}
