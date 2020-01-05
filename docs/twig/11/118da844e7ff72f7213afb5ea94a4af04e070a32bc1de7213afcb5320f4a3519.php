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

/* elements/property.html.twig */
class __TwigTemplate_5f2d123badca9e1d7ff823688506343f2b1f9461e2fff889dd57c4d152e44a32 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'property' => [$this, 'block_property'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $this->displayBlock('property', $context, $blocks);
    }

    public function block_property($context, array $blocks = [])
    {
        // line 2
        echo "    <div class=\"row-fluid\">
        <div class=\"span8 content class\">
            <a id=\"property_";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute(($context["property"] ?? null), "name", []), "html", null, true);
        echo "\" name=\"property_";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["property"] ?? null), "name", []), "html", null, true);
        echo "\" class=\"anchor\"></a>
            <article class=\"property\">
                <h3 class=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute(($context["property"] ?? null), "visibility", []), "html", null, true);
        echo " ";
        if ($this->getAttribute(($context["property"] ?? null), "deprecated", [])) {
            echo "deprecated";
        }
        echo "\">\$";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["property"] ?? null), "name", []), "html", null, true);
        echo "</h3>
                <pre class=\"signature\">\$";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute(($context["property"] ?? null), "name", []), "html", null, true);
        if ($this->getAttribute(($context["property"] ?? null), "types", [])) {
            echo " : ";
            echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute(($context["property"] ?? null), "types", []), "|"), "html", null, true);
        }
        echo "</pre>
                <p><em>";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute(($context["property"] ?? null), "summary", []), "html", null, true);
        echo "</em></p>
                ";
        // line 9
        echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute(($context["property"] ?? null), "description", [])]);
        echo "

                ";
        // line 11
        if ($this->getAttribute(($context["property"] ?? null), "types", [])) {
            // line 12
            echo "                <h4>Type</h4>
                ";
            // line 13
            echo twig_join_filter(call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["property"] ?? null), "types", [])]), "|");
            echo "
                ";
            // line 14
            if ($this->getAttribute($this->getAttribute($this->getAttribute(($context["property"] ?? null), "var", []), 0, []), "description", [])) {
                echo "&mdash; ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute(($context["property"] ?? null), "var", []), 0, []), "description", []), "html", null, true);
            }
            // line 15
            echo "                ";
        }
        // line 16
        echo "            </article>
        </div>
        <aside class=\"span4 detailsbar\">
            <h1><i class=\"icon-arrow-down\"></i></h1>
            ";
        // line 20
        if ($this->getAttribute(($context["property"] ?? null), "deprecated", [])) {
            // line 21
            echo "                <aside class=\"alert alert-block alert-error\">
                    <h4>Deprecated</h4>
                    ";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["property"] ?? null), "tags", []), "deprecated", []), 0, [], "array"), "description", []), "html", null, true);
            echo "
                </aside>
            ";
        }
        // line 26
        echo "            <dl>
                ";
        // line 27
        if (( !(null === $this->getAttribute(($context["node"] ?? null), "parent", [])) && ($this->getAttribute($this->getAttribute(($context["property"] ?? null), "parent", []), "fullyQualifiedStructuralElementName", []) != $this->getAttribute(($context["node"] ?? null), "fullyQualifiedStructuralElementName", [])))) {
            // line 28
            echo "                    <dt>Inherited from</dt>
                    <dd><a href=\"";
            // line 29
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('route')->getCallable(), [$this->getAttribute(($context["property"] ?? null), "parent", []), "url"]), "html", null, true);
            echo "\"><div class=\"path-wrapper\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["property"] ?? null), "parent", []), "fullyQualifiedStructuralElementName", []), "html", null, true);
            echo "</div></a></dd>
                ";
        }
        // line 31
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["property"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "link", 1 => "see"])) {
                // line 32
                echo "                    ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 33
                    echo "                        <dt>See also</dt>
                    ";
                }
                // line 35
                echo "                    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 36
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
                // line 38
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "                ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["property"] ?? null), "tags", []));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (twig_in_filter($context["tagName"], [0 => "uses"])) {
                // line 40
                echo "                    ";
                if ($this->getAttribute($context["loop"], "first", [])) {
                    // line 41
                    echo "                        <dt>Uses</dt>
                    ";
                }
                // line 43
                echo "                    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 44
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
                // line 46
                echo "                ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 47
        echo "            </dl>
            <h2>Tags</h2>
            <table class=\"table table-condensed\">
                ";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["property"] ?? null), "tags", []));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["tagName"] => $context["tags"]) {
            if (!twig_in_filter($context["tagName"], [0 => "link", 1 => "see", 2 => "access", 3 => "var", 4 => "deprecated", 5 => "uses"])) {
                // line 51
                echo "                    <tr>
                        <th>
                            ";
                // line 53
                echo twig_escape_filter($this->env, $context["tagName"], "html", null, true);
                echo "
                        </th>
                        <td>
                            ";
                // line 56
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["tags"]);
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 57
                    echo "                                ";
                    if ($this->getAttribute($context["tag"], "version", [])) {
                        echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], "version", []), "html", null, true);
                    }
                    // line 58
                    echo "                                ";
                    echo call_user_func_array($this->env->getFilter('markdown')->getCallable(), [$this->getAttribute($context["tag"], "description", [])]);
                    echo "
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 60
                echo "                        </td>
                    </tr>
                ";
                $context['_iterated'] = true;
            }
        }
        if (!$context['_iterated']) {
            // line 63
            echo "                    <tr><td colspan=\"2\"><em>None found</em></td></tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['tagName'], $context['tags'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 65
        echo "            </table>
        </aside>
    </div>
";
    }

    public function getTemplateName()
    {
        return "elements/property.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  269 => 65,  262 => 63,  254 => 60,  245 => 58,  240 => 57,  236 => 56,  230 => 53,  226 => 51,  220 => 50,  215 => 47,  205 => 46,  194 => 44,  189 => 43,  185 => 41,  182 => 40,  170 => 39,  160 => 38,  149 => 36,  144 => 35,  140 => 33,  137 => 32,  125 => 31,  118 => 29,  115 => 28,  113 => 27,  110 => 26,  104 => 23,  100 => 21,  98 => 20,  92 => 16,  89 => 15,  84 => 14,  80 => 13,  77 => 12,  75 => 11,  70 => 9,  66 => 8,  58 => 7,  48 => 6,  41 => 4,  37 => 2,  31 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "elements/property.html.twig", "phar:///home/toby/development/phpDocumentor.phar/src/phpDocumentor/../../data/templates/../templates/clean/elements/property.html.twig");
    }
}
