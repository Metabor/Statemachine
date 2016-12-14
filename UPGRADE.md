# Upgrade to 2.0 (formal 1.2)

## checkTransition now use external Context

External Context can now optionaly provided.
This feature was first planded to be version 1.2.0 / 1.2.1, but because of BC breaks (if Statemachine class was overwritten) it needs to be moved to 2.0. Except this change 2.0 is full compatible to 1.1. version.


# Upgrade to 1.1

## optional visualizing of the process graph by using clue/graph 

To reduce dependencies, the visualizing of the process graph by using clue/graph is now optional.
If you want to display process-graphs with GraphViz you have to add the clue/graph library to your
composer.json

```JSON
{
    "require": {
        "clue/graph": "~0.7"
    }
}
```

## Changed graph generation class from inheritance to composite 

To make graph part unit testable, the class was changed from inheritance from the graph class to use it as a composite.

The classname was also changed from Graph to GraphBuilder to make the change more visible.

Please change something like

```PHP
$graph = new \Metabor\Statemachine\Graph\Graph();
$graph->addStateCollection($process);
// ...
$graphViz = new \Fhaculty\Graph\GraphViz($graph);
```

to

```PHP
$graphBuilder = new \Metabor\Statemachine\Graph\GraphBuilder(new \Fhaculty\Graph\Graph());
$graphBuilder->addStateCollection($process);
// ...
$graphViz = new \Fhaculty\Graph\GraphViz($graphBuilder->getGraph());
```

## removed example code from library 

To slides and the example code from the conference talk have moved to the new repository [metabor/statemachine-example](https://github.com/Metabor/Statemachine-Example).
