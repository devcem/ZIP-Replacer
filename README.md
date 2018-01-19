# ZIP-Replacer
This program allows you to replace HTML codes in zip files. If you have hundreds of zip files that you have to extract, open, find the line and replace with new code case, you can use this easy PHP function to do this job.

# How to use?
zipReplacer is a function, only thing that you have to is changing the variables and run the PHP code. It will extract all zip files to temp folder and zip those files to output file with same file structure.

```
zipReplacer($find, $replace, $folderToScan);
```