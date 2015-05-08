TinCan Package Viewer
=====================

This plugin allows you to upload TinCan packages into Elgg, and collect reported progress information.

### What it does

* Allows you to upload a TinCan package as a zip archive
* Allows users to view the contents of the package
* Allows you to track user progress (see tincan_xapi plugin for LRS integration)

### Acknowledgements

The plugin has been commissioned and sponsored by Bodyology School of Massage.

### Security considerations

* By default, only administrators are allowed to upload packages.
Use ```'container_permissions_check','object'``` with high priority to change that behaviour.
* Please consider uploaded packages as a vector for _potential XSS attack_. Only allow uploads by users you trust.

### Notes

* The plugin has been built for compatibility with Articulate software. Presumably, if TinCan it is what it says it is,
it should work smoothly with other tools that generate TinCan compatible e-learning packages.
* When creating your zip packages, make sure tincan.xml is the in the root of the your archive (not in a nested folder)
