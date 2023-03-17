# Included `PHP` Files

Use the `/inc` directory to declare any theme functionality. All files in this directory are imported inside of `functions.php`. 

## Directories

The `/inc` directory is organized into subdirectories based on the functionality/purpose of the code. These directories can be modified as needed, but the following structure is recommended:

```text
inc/
└─── acf/               (ACF setup, blocks and other functionality)
└─── functions/         (general functions that don't fit into any other directory)
└─── helpers/           (helper files)
└─── hooks/             (theme hooks)
└─── setup/             (functions relating to the theme setup)
└─── shortcodes/        (shortcode registrations)
└─── template-tags/     (functions that render markup for use in theme templates)
└─── compatibility.php  (checks theme compatibility with current WP and PHP versions)
└─── dependency.php     (checks theme dependencies and adds notifications)
└─── optimization.php   (theme optimization)
└─── README.md          (this file)
```

## Filenames

As a general rule, each `.php` file should contain a single function/action which should match the name of the file (replacing underscores with hyphens in the filename).

For example, `function demo_function() {...}` would be declared in a file named `demo-function.php` and stored inside an appropriate `/inc` sub-directory.