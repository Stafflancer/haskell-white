# Bopper <!-- omit in toc -->

A starter theme from Bop Design. <https://www.bopdesign.com/>

[![Bop Design. The B2B Marketing Agency. Let’s make it happen.](https://dev-bopdesign.pantheonsite.io/wp-content/uploads/2023/01/bopper-banner.png)](https://www.bopdesign.com/contact/)

## Table of Contents <!-- omit in toc -->

- [Introduction](#introduction)
- [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Advanced](#advanced)
- [Setup](#setup)
    - [Development](#development)
- [Contributing and Support](#contributing-and-support)

## Introduction

Hi. I'm a starter theme called `bopper`. I'm a theme meant for hacking so don't use me as a Parent Theme. Instead, try turning me into the next, most awesome, WordPress theme out there. That's what I'm here for!

I feature some of the web's most proven technologies like: [Bootstrap](https://getbootstrap.com/), [npm](https://www.npmjs.com/), [webpack](https://webpack.js.org/), [Sass](http://sass-lang.com/), and [PostCSS](https://github.com/postcss/postcss). To help you write clean code (that meets [WordPress standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/)), we tap into [@wordpress/scripts](https://developer.wordpress.org/block-editor/packages/packages-scripts/) for linting CSS and JavaScript. Did I mention that I'm also accessible? Yup. I pass both WCAG 2.1AA and Section 508 standards out of the box.

Looking to use some of our custom Gutenberg blocks? It's easy! Blocks come with the theme and are located in the `/wp-content/themes/your-theme/blocks` folder.


## Getting Started

### Prerequisites

Because I compile and bundle assets via NPM scripts, basic knowledge of the command line and the following dependencies are required:

- [Node](https://nodejs.org) (v14+)
- [NPM](https://npmjs.com) (v7+)

### Advanced

If you want to set me up manually:

#### Find & Replace

You'll need to change all instances of the names: `_bopper`.

- Search for: `'_bopper'` and replace with: `'project-name'` (inside single quotations) to capture the text domain
- Search for: `"_bopper"` and replace with: `"project-name"` (inside double quotations) to capture the text domain
- Search for: `_bopper_` and replace with: `project-name_` to capture all the function names
- Search for: `Text Domain: _bopper` and replace with: `Text Domain: project-name` in style.css
- Search for (and include the leading space): ` _bopper` and replace with: ` Project Name` (with a space before it) to capture DocBlocks
- Search for: `_bopper-` and replace with: `project-name-` to capture prefixed handles
- Search for `_bopper.pot` and replace with: `project-name.pot` to capture translation files
- Edit the theme information in the header of style.scss to meet your needs

## Contributing and Support

bopper is free software, and is released under the terms of the GNU General Public License version 3 or any later version. See `LICENSE.md` for complete license.
