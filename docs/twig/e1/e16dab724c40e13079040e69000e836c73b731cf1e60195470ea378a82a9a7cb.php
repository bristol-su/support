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

/* /class.html.twig */
class __TwigTemplate_7718232d7215be2533fe37e09884b554eba5f0f4f68483ada15932edc7205171 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $_trait_0 = $this->loadTemplate("base/class.sidebar.html.twig", "/class.html.twig", 4);
        // line 4
        if (!$_trait_0->isTraitable()) {
            throw new RuntimeError('Template "'."base/class.sidebar.html.twig".'" cannot be used as a trait.', 4, $this->getSourceContext());
        }
        $_trait_0_blocks = $_trait_0->getBlocks();

        $this->traits = $_trait_0_blocks;

        $this->blocks = array_merge(
            $this->traits,
            [
                'title' => [$this, 'block_title'],
                'content' => [$this, 'block_content'],
            ]
        );
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["macros"] = $this->loadTemplate("base/macros.html.twig", "/class.html.twig", 3)->unwrap();
        // line 1
        $this->parent = $this->loadTemplate("layout.html.twig", "/class.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 6
    public function block_title($context, array $blocks = [])
    {
        // line 7
        echo "    ";
        $this->displayParentBlock("title", $context, $blocks);
        echo " &raquo; ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "FullyQualifiedStructuralElementName", []), "html", null, true);
        echo "
";
    }

    // line 10
    public function block_content($context, array $blocks = [])
    {
        // line 11
        echo "    <style>
        .deprecated h2 {
            text-decoration: line-through;
        }
    </style>
    <div class=\"row\">
        <div class=\"span4\">
            ";
        // line 18
        $this->displayBlock("sidebar", $context, $blocks);
        echo "
        </div>

        <div class=\"span8\">
            <div class=\"element class\">
                <h1>";
        // line 23
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "</h1>
                <small style=\"display: block; text-align: right\">
                    ";
        // line 25
        if ($this->getAttribute(($context["node"] ?? null), "parent", [])) {
            // line 26
            echo "                        Extends ";
            echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "parent", [])]), ", ");
            echo "
                    ";
        }
        // line 28
        echo "                    ";
        if (twig_length_filter($this->env, $this->getAttribute(($context["node"] ?? null), "interfaces", []))) {
            // line 29
            echo "                        Implements ";
            echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "interfaces", [])]), ", ");
            echo "
                    ";
        }
        // line 31
        echo "                </small>
                <p class=\"short_description\">";
        // line 32
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "summary", []), "html", null, true);
        echo "</p>
                <div class=\"details\">
                    <div class=\"long_description\">
                        ";
        // line 35
        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "description", [])]);
        echo "
                    </div>
                    <table class=\"table table-bordered\">
                        ";
        // line 38
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["node"] ?? null), "tags", []));
        foreach ($context['_seq'] as $context["_key"] => $context["tagList"]) {
            // line 39
            echo "                            ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["tagList"]);
            foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                if (!twig_in_filter($this->getAttribute($context["tag"], "name", []), [0 => "method", 1 => "property"])) {
                    // line 40
                    echo "                                <tr>
                                    <th>
                                        ";
                    // line 42
                    echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "name", []), "html", null, true);
                    echo "
                                        ";
                    // line 43
                    if ($this->getAttribute($context["tag"], "type", [])) {
                        // line 44
                        echo "                                            ";
                        echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "type", [])]), "|");
                        echo "
                                        ";
                    }
                    // line 46
                    echo "                                    </th>
                                    <td>
                                        ";
                    // line 48
                    if ((($this->getAttribute($context["tag"], "name", []) == "since") || "deprecated")) {
                        // line 49
                        echo "                                            ";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                        echo "
                                        ";
                    }
                    // line 51
                    echo "                                        ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                                    </td>
                                </tr>
                            ";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 55
            echo "                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tagList'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 56
        echo "                    </table>

                    <h3><i class=\"icon-custom icon-method\"></i> Methods</h3>
                    ";
        // line 59
        $context["specialMethods"] = (($this->getAttribute(($context["node"] ?? null), "magicMethods", [])) ? ($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedMethods", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicMethods", [])], "method")) : ($this->getAttribute(($context["node"] ?? null), "inheritedMethods", [])));
        // line 60
        echo "                    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(call_user_func_array($this->env->getFilter('sort_asc')->getCallable(), ["asc", $this->getAttribute($this->getAttribute(($context["node"] ?? null), "methods", []), "merge", [0 => ($context["specialMethods"] ?? null)], "method")]));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            // line 61
            echo "                        <a id=\"method_";
            echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
            echo "\"></a>
                        <div class=\"element clickable method ";
            // line 62
            echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "visibility", []), "html", null, true);
            echo " ";
            echo (($this->getAttribute($context["method"], "deprecated", [])) ? ("deprecated") : (""));
            echo " method_";
            echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
            echo ((($this->getAttribute($this->getAttribute($context["method"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) ? (" inherited") : (""));
            echo "\" data-toggle=\"collapse\" data-target=\".method_";
            echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
            echo " .collapse\">
                            <h2>";
            // line 63
            echo twig_escape_filter($this->env, (($this->getAttribute($context["method"], "summary", [])) ? ($this->getAttribute($context["method"], "summary", [])) : ($this->getAttribute($context["method"], "name", []))), "html", null, true);
            echo "</h2>
                            <pre>";
            // line 64
            echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
            echo "(";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["method"], "arguments", []));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["argument"]) {
                (($this->getAttribute($context["argument"], "types", [])) ? (print (twig_escape_filter($this->env, (twig_join_filter($this->getAttribute($context["argument"], "types", []), "|") . " "), "html", null, true))) : (print ("")));
                echo (($this->getAttribute($context["argument"], "byReference", [])) ? ("&") : (""));
                echo twig_escape_filter($this->env, $this->getAttribute($context["argument"], "name", []), "html", null, true);
                (( !(null === $this->getAttribute($context["argument"], "default", []))) ? (print (twig_escape_filter($this->env, (" = " . $this->getAttribute($context["argument"], "default", [])), "html", null, true))) : (print ("")));
                if ( !$this->getAttribute($context["loop"], "last", [])) {
                    echo ", ";
                }
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['argument'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            echo ") ";
            (($this->getAttribute($this->getAttribute($context["method"], "response", []), "types", [])) ? (print (twig_escape_filter($this->env, (": " . twig_join_filter($this->getAttribute($this->getAttribute($context["method"], "response", []), "types", []), "|")), "html", null, true))) : (print ("")));
            echo "</pre>
                            <div class=\"labels\">
                                ";
            // line 66
            if (($this->getAttribute($this->getAttribute($context["method"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) {
                echo "<span class=\"label\">inherited</span>";
            }
            // line 67
            echo "                                ";
            if ($this->getAttribute($context["method"], "static", [])) {
                echo "<span class=\"label\">static</span>";
            }
            // line 68
            echo "                                ";
            if ($this->getAttribute($context["method"], "final", [])) {
                echo "<span class=\"label\">final</span>";
            }
            // line 69
            echo "                                ";
            if ($this->getAttribute($context["method"], "abstract", [])) {
                echo "<span class=\"label\">abstract</span>";
            }
            // line 70
            echo "                                ";
            if ($this->getAttribute($this->getAttribute($context["method"], "tags", [], "any", false, true), "api", [], "any", true, true)) {
                echo "<span class=\"label label-info\">api</span>";
            }
            // line 71
            echo "                            </div>

                            ";
            // line 73
            $this->loadTemplate("method.html.twig", "/class.html.twig", 73)->display(twig_array_merge($context, ["method" => $context["method"]]));
            // line 74
            echo "
                        </div>
                    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 77
        echo "
                    ";
        // line 78
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["node"] ?? null), "constants", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "inheritedConstants", [])], "method")) > 0)) {
            // line 79
            echo "                        <h3><i class=\"icon-custom icon-constant\"></i> Constants</h3>
                        ";
            // line 80
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "constants", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "inheritedConstants", [])], "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
                // line 81
                echo "                            <a id=\"constant_";
                echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
                echo "\"> </a>
                            <div class=\"element clickable constant ";
                // line 82
                echo (($this->getAttribute($context["constant"], "deprecated", [])) ? ("deprecated") : (""));
                echo " constant_";
                echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
                echo ((($this->getAttribute($this->getAttribute($context["constant"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) ? (" inherited") : (""));
                echo "\" data-toggle=\"collapse\" data-target=\".constant_";
                echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
                echo " .collapse\">
                                <h2>";
                // line 83
                echo twig_escape_filter($this->env, (($this->getAttribute($context["constant"], "summary", [])) ? ($this->getAttribute($context["constant"], "summary", [])) : ($this->getAttribute($context["constant"], "name", []))), "html", null, true);
                echo "</h2>
                                <pre>";
                // line 84
                echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
                echo "</pre>
                                <div class=\"labels\">
                                    ";
                // line 86
                if (($this->getAttribute($this->getAttribute($context["constant"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) {
                    echo "<span class=\"label\">inherited</span>";
                }
                // line 87
                echo "                                </div>
                                <div class=\"row collapse\">
                                    <div class=\"detail-description\">
                                        <div class=\"long_description\">";
                // line 90
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["constant"], "description", [])]);
                echo "</div>
                                        <table class=\"table\">
                                            ";
                // line 92
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["constant"], "tags", []));
                foreach ($context['_seq'] as $context["_key"] => $context["tagList"]) {
                    // line 93
                    echo "                                                <tr>
                                                    <th>
                                                        ";
                    // line 95
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["tagList"], 0, []), "name", []), "html", null, true);
                    echo "
                                                    </th>
                                                    <td>
                                                        ";
                    // line 98
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["tagList"]);
                    foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                        // line 99
                        echo "                                                            ";
                        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                        echo "
                                                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 101
                    echo "                                                    </td>
                                                </tr>
                                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tagList'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 104
                echo "                                        </table>
                                    </div>
                                </div>
                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 109
            echo "                    ";
        }
        // line 110
        echo "
                    ";
        // line 111
        $context["specialProperties"] = (($this->getAttribute(($context["node"] ?? null), "magicProperties", [])) ? ($this->getAttribute($this->getAttribute(($context["node"] ?? null), "inheritedProperties", []), "merge", [0 => $this->getAttribute(($context["node"] ?? null), "magicProperties", [])], "method")) : ($this->getAttribute(($context["node"] ?? null), "inheritedProperties", [])));
        // line 112
        echo "                    ";
        if ((twig_length_filter($this->env, $this->getAttribute($this->getAttribute(($context["node"] ?? null), "properties", []), "merge", [0 => ($context["specialProperties"] ?? null)], "method")) > 0)) {
            // line 113
            echo "                        <h3><i class=\"icon-custom icon-property\"></i> Properties</h3>
                        ";
            // line 114
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute(($context["node"] ?? null), "properties", []), "merge", [0 => ($context["specialProperties"] ?? null)], "method"));
            foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
                // line 115
                echo "                            <a id=\"property_";
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
                echo "\"> </a>
                            <div class=\"element clickable property ";
                // line 116
                echo (($this->getAttribute($context["property"], "deprecated", [])) ? ("deprecated") : (""));
                echo " ";
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "visibility", []), "html", null, true);
                echo " property_";
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
                echo ((($this->getAttribute($this->getAttribute($context["property"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) ? (" inherited") : (""));
                echo "\" data-toggle=\"collapse\" data-target=\".property_";
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
                echo " .collapse\">
                                <h2>";
                // line 117
                echo twig_escape_filter($this->env, (($this->getAttribute($context["property"], "summary", [])) ? ($this->getAttribute($context["property"], "summary", [])) : ((($this->getAttribute($this->getAttribute($this->getAttribute($context["property"], "var", []), 0, []), "description", [])) ? ($this->getAttribute($this->getAttribute($this->getAttribute($context["property"], "var", []), 0, []), "description", [])) : ($this->getAttribute($context["property"], "name", []))))), "html", null, true);
                echo "</h2>
                                <pre>";
                // line 118
                echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
                echo " : ";
                echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute($context["property"], "types", []), "|"), "html", null, true);
                echo "</pre>
                                <div class=\"labels\">
                                    ";
                // line 120
                if (($this->getAttribute($this->getAttribute($context["property"], "parent", []), "name", []) != $this->getAttribute(($context["node"] ?? null), "name", []))) {
                    echo "<span class=\"label\">inherited</span>";
                }
                // line 121
                echo "                                    ";
                if ($this->getAttribute($context["property"], "static", [])) {
                    echo "<span class=\"label\">static</span>";
                }
                // line 122
                echo "                                </div>
                                <div class=\"row collapse\">
                                    <div class=\"detail-description\">
                                        <div class=\"long_description\">";
                // line 125
                echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["property"], "description", [])]);
                echo "</div>

                                        <table class=\"table\">
                                            ";
                // line 128
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["property"], "tags", []));
                foreach ($context['_seq'] as $context["_key"] => $context["tagList"]) {
                    // line 129
                    echo "                                                <tr>
                                                    <th>
                                                        ";
                    // line 131
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($context["tagList"], 0, []), "name", []), "html", null, true);
                    echo "
                                                    </th>
                                                    <td>
                                                        ";
                    // line 134
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["tagList"]);
                    foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                        // line 135
                        echo "                                                            ";
                        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                        echo "
                                                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 137
                    echo "                                                    </td>
                                                </tr>
                                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tagList'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 140
                echo "                                        </table>

                                        ";
                // line 142
                if (($this->getAttribute($context["property"], "types", []) && (twig_join_filter($this->getAttribute($context["property"], "types", [])) != "void"))) {
                    // line 143
                    echo "                                            <h3>Type(s)</h3>
                                            <code>";
                    // line 144
                    echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["property"], "types", [])]), "|");
                    echo "</code>
                                        ";
                }
                // line 146
                echo "                                    </div>
                                </div>
                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 150
            echo "                    ";
        }
        // line 151
        echo "                </div>
            </div>
            <a id=\"";
        // line 153
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "fullyQualifiedStructuralElementName", []), "html", null, true);
        echo "\"></a>
            <ul class=\"breadcrumb\">
                <li><a href=\"";
        // line 155
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), ["index.html"]), "html", null, true);
        echo "\"><i class=\"icon-custom icon-class\"></i></a></li>
                ";
        // line 156
        echo $context["macros"]->getbuildBreadcrumb($this->getAttribute(($context["node"] ?? null), "namespace", []));
        echo "
                <li class=\"active\"><span class=\"divider\">\\</span><a href=\"";
        // line 157
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('path')->getCallable(), [($context["node"] ?? null)]), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "name", []), "html", null, true);
        echo "</a></li>
            </ul>
        </div>
    </div>

";
    }

    public function getTemplateName()
    {
        return "/class.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  551 => 157,  547 => 156,  543 => 155,  538 => 153,  534 => 151,  531 => 150,  522 => 146,  517 => 144,  514 => 143,  512 => 142,  508 => 140,  500 => 137,  491 => 135,  487 => 134,  481 => 131,  477 => 129,  473 => 128,  467 => 125,  462 => 122,  457 => 121,  453 => 120,  446 => 118,  442 => 117,  431 => 116,  426 => 115,  422 => 114,  419 => 113,  416 => 112,  414 => 111,  411 => 110,  408 => 109,  398 => 104,  390 => 101,  381 => 99,  377 => 98,  371 => 95,  367 => 93,  363 => 92,  358 => 90,  353 => 87,  349 => 86,  344 => 84,  340 => 83,  331 => 82,  326 => 81,  322 => 80,  319 => 79,  317 => 78,  314 => 77,  298 => 74,  296 => 73,  292 => 71,  287 => 70,  282 => 69,  277 => 68,  272 => 67,  268 => 66,  225 => 64,  221 => 63,  210 => 62,  205 => 61,  187 => 60,  185 => 59,  180 => 56,  174 => 55,  162 => 51,  156 => 49,  154 => 48,  150 => 46,  144 => 44,  142 => 43,  138 => 42,  134 => 40,  128 => 39,  124 => 38,  118 => 35,  112 => 32,  109 => 31,  103 => 29,  100 => 28,  94 => 26,  92 => 25,  87 => 23,  79 => 18,  70 => 11,  67 => 10,  58 => 7,  55 => 6,  50 => 1,  48 => 3,  42 => 1,  23 => 4,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "/class.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/responsive-twig//class.html.twig");
    }
}
