
## Enlist Package

This is a spoova framework package for listing or renaming files in a directory. It can be used to rename file names and file extensions. While renaming file extensions can break the functionality of a file, some specific files extensions can be easily modified. Renaming files extensions that can be easily broken should be avoided. At most times, this class may be used to change extension of image files or text-related file. Although, some file extensions can be easily modified, yet it does not serve as converter for such files. In the case where a converter is needed, a converter tool should be employed. 

### Including the Enlist class

   > Run the command below in the terminal to require the package

   ```cmd
   composer require spoova/enlist
   ```

### Initializing the Enlist class

   > In your sample php file include the _vendor/autoload.php_ file and import the Enlist class

   ```php 
   <?php

   include_once "vendor/autoload.php";

   use Spoova\Enlist\Enlist;
   
   $Enlist = new Enlist;
   ```

### Set a file directory

   > In order to use the Enlist, the source directory must first be be specified

   ```php 
   $Enlist->source(__DIR__.'/images');
   ```

### Check if file directory is valid

   > We can check if the source directory supplied is valid through the ```sourceValid()``` method

   ```php 
   $Enlist->source(__DIR__.'/images');

   if($Enlist->sourceValid()) {

     echo "directory is valid";

   } else {

     echo "invalid source directory supplied";

   }
   ```

### List files in directory

   > The code below will list all files in a directory except hidden files

   ```php 
   $Enlist->source(__DIR__.'/images');

   if( $Enlist->sourceValid() ) {
       
       $files = $Enlist->dirFiles(); // or $Enlist->dirFiles('*')
       
       var_dump($files);

   } else { 

        echo "invalid url supplied";

   }
   ```

### List files in directory having jpg extension

   > The code below will list all files with .jpg files in a directory

   ```php 
   $Enlist->source(__DIR__.'/images');

   if( $Enlist->sourceValid() ){
       
       $files = $Enlist->dirFiles(['jpg']); // or $Enlist->dirFiles('jpg')
       
       var_dump($files);

   } else { 

        echo "invalid url supplied";

   }
   ```

### List hidden files in directory

   > The code below will list all files in specified directory having only extension name without any file name

   ```php 
   $Enlist->source(__DIR__.'/images');

   $files = $Enlist->dirFiles(['.']); // or $Enlist->dirFiles('.')

   var_dump($files);
   ```

### List hidden files in directory and files having png extension only

   > The code below will list all .png files in the source directory along with hidden files

   ```php 
   $Enlist->source(__DIR__.'/images');

   $files = $Enlist->dirFiles(['.','png']);
    
   var_dump($files); 
   ```

### List all files in directory including hidden

   > The code below will list all files including hidden files in a directory

   ```php 
   $Enlist->source(__DIR__.'/images');

   $files = $Enlist->dirFiles(['.*']); // or $Enlist->dirFiles(['.','*'])
    
   var_dump($files);
   ```

### Renaming Files in directory

   > Rename all file extensions in a directory to png except hidden files
   ```php
   $Enlist->source(__DIR__.'/images');

   $result = $Enlist->rename('png');

   var_dump($result);
   ```

   > The result of a renaming can also be obtained by suppling a second argument to ```rename()``` function
   ```php
   $Enlist->source(__DIR__.'/images');

   $Enlist->rename('png', $result);
    
   var_dump($result);
   ```

   > Rename only .jpg file extensions in a directory to png extension
   ```php
   $Enlist->source(__DIR__.'/images');

   $Enlist->rename('png', $result);
   
   var_dump($result);
   ```

   > Rename only .jpg file names in a directory with serial numbering
   ```php
   $Enlist->source(__DIR__.'/images');

   $Enlist->reNumber();
   $result = $Enlist->rename();
    
   var_dump($result);
   ```

   > Files can be renamed with serial numbers starting from a specific number using the ```startFrom()``` method

   ```php
   $Enlist->source(__DIR__.'/images', ['jpg']);

   $Enlist->reNumber()->startFrom(10);
   $result = $Enlist->rename();

   var_dump($result);
   ```

   > Renaming file names in a directory with serial numbering with a named prefix can be done using both the ```prefix()``` and ```reNumber()``` methods
   
   ```php 
   $Enlist->source(__DIR__.'/src/images');

   $Enlist->prefix('images-');
   $Enlist->reNumber();
   $Enlist->rename('jpg', $result);
    
   var_dump($result);
   ```
   
   > Spaces in file names can be replaced with another character through the  ```reSpace()``` method.
   
   ```php
   $Enlist->source(__DIR__.'/src/images', ['jpg']);

   $Enlist->reSpace("_"); //changes all spaces to underscore ( i.e _ )
   $Enlist->rename('jpg', $result);

   var_dump($result);
   ```
   
   > Smart url format can be applied to file name. This will remove special characters from file names
   
   ```php
   $Enlist->source(__DIR__.'/src/images', ['jpg']);

   $Enlist->reSpace("_"); //changes all spaces to underscore ( i.e _ )
   $Enlist->rename('jpg', $result);

   var_dump($result);
   ```

   > The ```view()``` method can be used to prevent ```rename()``` from actively renaming files. Only the expected output result will be seen as array list if no error occurs

   ```php
   $Enlist->source(__DIR__.'/src/images');

   $Enlist->view();
   $Enlist->prefix('images-');
   $Enlist->reNumber();
   $Enlist->rename('jpg', $result);
   
   var_dump($result);
   ```

   > Errors can be returned as text if debug mode is not turned on

   ```php
   $Enlist->source(__DIR__.'/src/images' );

   $Enlist->view();
   $Enlist->prefix('image-');
   $result = $Enlist->rename('.');
   
   if($result === false){
   
       var_dump($Enlist->error());
   
   }
   ```

   > Errors can also be fetched by turning debug mode on without throwing errors. In order to do this the ```debug()``` method must be turned on before ```rename()``` is called. Finally the ```debugs()``` method will return all backtraces where error occured. Note that the ```rename()``` or ```dirFiles()``` method are only executed if the specified source url is valid.

   ```php
   $Enlist->debug(); //turn on debugging without throwing error
   
   $Enlist->source(__DIR__.'/src/images') 
       
   $Enlist->view(); 
   $Enlist->prefix('image-');
   $Enlist->rename('.', $result);
    
   if(!$Enlist->debugs()){
   
       var_dump($result);
       
   }else{
           
       var_dump($Enlist->debugs());
   
   }
   ```

   > Debugs can also be fetched by supplying a referenced variable into the ```debug()``` method.

   ```php
   $Enlist->debug(); //turn on debugging without throwing error

   if( $Enlist->source(__DIR__.'/src/images') ) {

       $Enlist->view(); 
       $Enlist->prefix('image-');
       $Enlist->rename('.', $results);
       $Enlist->debugs($debugs);
       
       if(!$debugs){
    
         var_dump($results);
    
       } else {
    
         var_dump($debugs);
    
       } 

   }
   ```

   > Throwing errors can be enabled when the debug mode is enabled. This can be done by supplying an argument of ```"2"``` on the ```debug()``` method.

   ```php
   $Enlist->debug(2); //turn on debugging with ErrorException thrown

   $Enlist->source(__DIR__.'/src/images');

   $Enlist->view(); 
   $Enlist->prefix('image-');
   $Enlist->rename('.', $result);

   var_dump($result);
   ```

### Working with session 
In certain situations where files are renamed in a way that is not desired or one desires to reverse back to the previous file names, enlist supports the reversal of changes made to files only if there is no overiding of files. To reverse back to previous names, a session must be actively defined with the ```withSession()``` method and a unique key where the most recent changes are stored. If no session was started earlier the ```withSession()``` method will start a new session of its own. Once the enlist session name is defined, the ```reverse()``` method can be applied to revert changes. 

   > Allow Enlist to revert recent changes from session storage

   ```php
   if( $Enlist->source(__DIR__.'/src/images') ) {

       $Enlist->withSession('unique_session_name'); //set a session storage name
       $Enlist->prefix('image-');
       $result = $Enlist->rename('png');
    
       $Enlist->reverse($reversals); //reverse back to previous names
    
       var_dump($reversals);

   }
   ```

   > Session names can also be specified from the session storage. Reversals will be made only if the old filename still exists in the specified directory. Also, when a reversal 
   has been made, the stored session urls will be cleared out.

   ```php
   if( $Enlist->source(__DIR__.'/src/images') ) {

       $Enlist->withSession('my_enlist_session_name'); //set a session storage name
       $Enlist->prefix('image-');
       $result = $Enlist->rename('png');
    
       $Enlist->reverse($reversals, 'my_enlist_session_name'); //reverse back to previous names using specific storage name
    
       var_dump($reversals);

   }
   ```

#### Good Practices 
It is always good to set the renaming to view mode first to check how the final result of a list of renamed files will be before proceeding to rename the item to avoid issues like merging of conflicts which can lead to loss of file. 
