DataMapper-Minimal
==================

<p align="center">
  <img alt="" width="250" src="http://www.gifbin.com/bin/1232014623_seven_1.gif"/>
  </p>
Easily transform arrays and objects with different layouts into one format.
Think importing data from several sources in different formats to generate statistics or exports.

## Usage 

    $ composer require coderofsalvation/datamapper-minimal

And then 

    <? 
      use coderofsalvation\DataMapper;
      $datamapper = new DataMapper();
    ?> 

Lets say this is our desired object format: 

    class MyObject {
      public $name;
      public $id;
    }

But source A provide these object formats:

    [{ "short_name": "foo", "ID": 12 },{ "short_name": "bar", "ID":13}]

And oh no! source B has this object format:    

    <items> 
      <item id="15">
        <Name>Boo</Name>
      </item>
    </items>       

This is going to be a mess...or not?
Nope, we can just define transformations and convert them in batch:

    $datamapper->addMapping("A", array(
      array( "source" => "short_name", "destination" => "name", "transform" => function($s,&$d){ return $s->short_name;    } ), 
      array( "source" => "id",         "destination" => "id",   "transform" => function($s,&$d){ return $s->id;            } ) 
    ));

    $datamapper->addMapping("B", array(
      array( "source" => "Name", "destination" => "name",       "transform" => function($s,&$d){ return $s->Name;                } ), 
      array( "source" => "Id",   "destination" => "id",         "transform" => function($s,&$d){ return $s->getAttribute("id");  } )
    ));

    // lets do it!
    $items = [];
    foreach( $Aitems as $item ) $items[] = $datamapper->map("A", $item, new MyObject() );
    foreach( $Bitems as $item ) $items[] = $datamapper->map("B", $item, new MyObject() );

    print_r($items);
    // voila there you go :D

## License

BSD
