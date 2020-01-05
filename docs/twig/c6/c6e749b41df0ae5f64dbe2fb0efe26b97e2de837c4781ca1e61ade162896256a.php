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

/* elements/constant.html.twig */
class __TwigTemplate_e75c43426c15385743f77040a5f4723e76f5e0c714a2135164f3438ba9a26005 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'constant' => [$this, 'block_constant'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $this->displayBlock('constant', $context, $blocks);
    }

    public function block_constant($context, array $blocks = [])
    {
        // line 2
        echo "    <div class=\"row-fluid\">
        <div class=\"span8 content class\">
            <a id=\"constant_";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute(($context["constant"] ?? null), "name", []), "html", null, true);
        echo "\" name=\"constant_";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["constant"] ?? null), "name", []), "html", null, true);
        echo "\" class=\"anchor\"></a>
            <article id=\"constant_";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute(($context["constant"] ?? null), "name", []), "html", null, true);
        echo "\" class=\"constant\">
                <h3 class=\"";
        // line 6
        if ($this->getAttribute(($context["constant"] ?? null), "deprecated", [])) {
            echo "deprecated";
        }
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["constant"] ?? null), "name", []), "html", null, true);
        echo "</h3>
                <pre class=\"signature\">";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute(($context["constant"] ?? null), "name", []), "html", null, true);
        echo " = ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["constant"] ?? null), "value", []), "html", null, true);
        if ( !twig_test_empty($this->getAttribute(($context["constant"] ?? null), "types", []))) {
            echo " : ";
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute(($context["constant"] ?? null), "types", []), "|"), "html", null, true);
        }
        echo "</pre>
                <p><em>";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute(($context["constant"] ?? null), "summary", []), "html", null, true);
        echo "</em></p>
                ";
        // line 9
        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["constant"] ?? null), "description", [])]);
        echo "
            </article>
        </div>
        <aside class=\"span4 detailsbar\">
            <h1><i class=\"icon-arrow-down\"></i></h1>
            ";
        // line 14
        if ($this->getAttribute(($context["constant"] ?? null), "deprecated", [])) {
            // line 15
            echo "                <aside class=\"alert alert-block alert-error\">
                    <h4>Deprecated</h4>
                    ";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["constant"] ?? null), "tags", []), "deprecated", []), 0, [], "array"), "description", []), "html", null, true);
            echo "
                </aside>
            ";
        }
        // line 20
        echo "            <dl>
                ";
        // line 21
        if ((null === $this->getAttribute(($context["node"] ?? null), "parent", []))) {
            // line 22
            echo "                <dt>File</dt>
                <dd><a href=\"";
            // line 23
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["node"] ?? null), "file", []), "url"]), "html", null, true);
            echo "\"><div class=\"path-wrapper\">";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["node"] ?? null), "path", []), "html", null, true);
            echo "</div></a></dd>
                ";
        }
        // line 25
        echo "                ";
        if (( !(null === $this->getAttribute(($context["node"] ?? null), "parent", [])) && ($this->getAttribute($this->getAttribute(($context["constant"] ?? null), "parent", []), "fullyQualifiedStructuralElementName", []) != $this->getAttribute(($context["node"] ?? null), "fullyQualifiedStructuralElementName", [])))) {
            // line 26
            echo "                    <dt>Inherited from</dt>
                    <dd><a href=\"";
            // line 27
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["constant"] ?? null), "parent", []), "url"]), "html", null, true);
            echo "\"><div class=\"path-wrapper\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["constant"] ?? null), "parent", []), "fullyQualifiedStructuralElementName", []), "html", null, true);
            echo "</div></a></dd>
                ";
        }
        // line 29
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["constant"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "link", 1 => "see"])) {
                // line 30
                echo "                    ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 31
                    echo "                        <dt>See also</dt>
                    ";
                }
                // line 33
                echo "                    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 34
                    echo "                        <dd><a href=\"";
                    echo twig_escape_filter($this->env, ((call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) ? (call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"])) : ($this->getAttribute($context["tag"], "link", []))), "html", null, true);
                    echo "\"><span class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</span></a></dd>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 36
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 37
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["constant"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "uses"])) {
                // line 38
                echo "                    ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 39
                    echo "                        <dt>Uses</dt>
                    ";
                }
                // line 41
                echo "                    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 42
                    echo "                        <dd><a href=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute($context["tag"], "reference", []), "url"]), "html", null, true);
                    echo "\"><span class=\"namespace-wrapper\">";
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["tag"], "description", [])) ? ($this->getAttribute($context["tag"], "description", [])) : ($this->getAttribute($context["tag"], "reference", []))), "html", null, true);
                    echo "</span></a></dd>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 44
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 45
        echo "            </dl>
            <h2>Tags</h2>
            <table class=\"table table-condensed\">
                ";
        // line 48
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["constant"] ?? null), "tags", []));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (!twig_in_filter($context["tagName"], [0 => "link", 1 => "see", 2 => "var", 3 => "deprecated", 4 => "uses"])) {
                // line 49
                echo "                    <tr>
                        <th>
                            ";
                // line 51
                echo twig_escape_filter($this->env, $context["tagName"], "html", null, true);
                echo "
                        </th>
                        <td>
                            ";
                // line 54
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 55
                    echo "                                ";
                    if ($this->getAttribute($context["tag"], "version", [])) {
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                    }
                    // line 56
                    echo "                                ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 58
                echo "                        </td>
                    </tr>
                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 61
            echo "                    <tr><td colspan=\"2\"><em>None found</em></td></tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
        echo "            </table>
        </aside>
    </div>
";
    }

    public function getTemplateName()
    {
        return "elements/constant.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  265 => 63,  258 => 61,  250 => 58,  241 => 56,  236 => 55,  232 => 54,  226 => 51,  222 => 49,  216 => 48,  211 => 45,  201 => 44,  190 => 42,  185 => 41,  181 => 39,  178 => 38,  166 => 37,  156 => 36,  145 => 34,  140 => 33,  136 => 31,  133 => 30,  121 => 29,  114 => 27,  111 => 26,  108 => 25,  101 => 23,  98 => 22,  96 => 21,  93 => 20,  87 => 17,  83 => 15,  81 => 14,  73 => 9,  69 => 8,  59 => 7,  51 => 6,  47 => 5,  41 => 4,  37 => 2,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "elements/constant.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean/elements/constant.html.twig");
    }
}
