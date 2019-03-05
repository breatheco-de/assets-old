**Day 10 -  Introduction to React.js**

**Welcome class. Take any questions about DOM/Javascript (10 min)**

**Today we will be covering the beginning of creating our first React.js Applications!**

**This topic will be broken up in a few lessons with the overview being today and more detail over the coming days.**

**PLEASE DON’T MISS CLASS and make sure to go to the downtown 4geeks office on Saturday for study session****.**

1. **Let’s review Objects again in a bit more detail.**

    1. **Objects are like complex arrays**

        1. Rather than just values, information is stored in Key/Value pairs

        2. Let’s Use the Car website example again

            1. We are building a car website. This site will store instances of specific cars. All cars are stored in Objects that we have defined so that we can display them on the front end for the user. 

            2. Here is an example:
var car = {make: "Ford", model: “Explorer”, color: “gray”, Mileage: “45000”}

            3. In practice, objects may be much larger than this and may contain many different keys. These Keys are also called Object Properties.

2. **To access an object’s properties, you can do so in the following ways:**

        3. objectName.propertyName 

            4. Using our example above:  car.make;

        4. or objectName["propertyName"]

            5. Again, using the above example:  car["make"];

    3. **Once you have defined an object, to change a value in the object you simply need to:**

        5. set the property equal to something like this:
car.make = "Lexus";

            6. notice we didn’t re-declare the variable because we are not initializing the variable again. We are just changing the value for a given property.

4. **Objects vs Classes**

        6. When You defined an Object just now, we created an INSTANCE of a car.

        7. However, how does your program know what a "car" is? This is where classes come in.

        8. The Class is a prototype of what it means to be a "car". Or more simply put, it’s the definition for whatever objects you will create.

        9. If you want to create ice creams, you would define all of the properties an ice cream can have and then create a class for it.

            7. If we defined an Ice Cream class, we would map out the properties as follows:
Ice Cream
Flavor
Color
Ingredients

            8. Let’s look at the syntax: [https://breatheco.de/en/lesson/object-oriented-programming/#class-syntax](https://breatheco.de/en/lesson/object-oriented-programming/#class-syntax) 

        10. classes can have simpled key/value pairs or have calculated properties (methods) that perform some action. [https://breatheco.de/en/lesson/object-oriented-programming/#calculated-properties](https://breatheco.de/en/lesson/object-oriented-programming/#calculated-properties) 

            9. example method could be to calculate a car’s range

                1. function calcRange(mpg,tank-size){return mpg*tank-size;}

                2. you could store this in the constructor as follows:
car.range = ( )=>{return mpg*tank-size;};
or
this.range = function(){return mpg*tank-size;};

2. **What is React?**

    5. A front end javascript library that is used to create advanced HTML/CSS/JS projects.

    6. It’s highly in demand in the job market right now and growing.

3. **Why React?**

    7. No more DOM. React handles all of that. You will create your own components and tell react what component to render and when; react handles all interaction with DOM on the front end.

    8. It’s component based, which means that you will create components to handle tasks and then reuse them throughout your application.

    9. It’s much faster than traditional javascript. Instead of re-rendering the whole page when a single change occurs, React renders individual items that change. 

    10. React.js comes with JSX, a special language that will let you build HTML on the Javascript side without having to wrap it within quotes (make it a string). 

4. **Export -> Import modules**

    11. Your entire website will become a component with sub-components in it.

        11. [https://breatheco.de/en/lesson/react-js/#components](https://breatheco.de/en/lesson/react-js/#components)

        12. This means that you have to somehow link your sub components into your main components.

    12. When you declare a component, you can use it within the same file.

    13. When you create a  component that is in another file, you will have a to export the component so that you can use it in another view.

        13. using 
export default myFunction;
at the end of your function or class

        14. or
export const render = () => (
  // Some other JSX
);

        15. or
export var render = function() {
  return (
    // Some other JSX
  );
};

        16. For a class, you can also use:
export class Foo extends React.Component{  
  constructor(props){
    super(props);
  }

  render() {
    return (
      // Some JSX
    )      
  }
}

    14. To use these components in your application you would have to import them into the view where they will be used.

        17. Let’s say you export a class named Lib in a file named ‘lib’

            10. import { Lib } from './jsx/lib';

                3. without default

                4. imports a part of the file

            11. import Lib from './jsx/lib';

                5. with default

                6. imports the whole file

5. **You can create your own tags**

    15. Before, you learned that HTML Tags are set functions that the browser knows how to interpret.

    16. Now in React, You can create your own tags by creating usable components.

6. **Create a Component like a Class**

    17. [https://breatheco.de/en/lesson/object-oriented-programming/#class-syntax](https://breatheco.de/en/lesson/object-oriented-programming/#class-syntax) 

    18. Class components are more complex. They let your component use State and also lifestyle methods

    19. Lifestyle methods are things like componentWillMount() or  componentDidMount() that help to control at what point in the livecycle of the component your code executes.

    20. Lifecycle of React component:   [https://breatheco.de/en/lesson/react-js/#component-lifecycle](https://breatheco.de/en/lesson/react-js/#component-lifecycle) 

7. **Create a Component like a Function**

    21. Less complex component that should just return HTML with whatever the function should display.

    22. Best practice is to plan/create your component as a functional component unless you absolutely need it to be a Class due to complexity or the need for state/lifestyle methods.

8. **Use of the render method**

    23. The following example goes on the board:
import React from 'react';

export class EntireWebsiteLayout extends React.Component{
    
    render(){
        return (
            <div>
                <Home />
                <AboutUs />
                <ContactUs />
            </div>
        );
    }
}

    24. This example shows a few things

        18. Import statement declares that you are creating a react component.

        19. export statement shows that you are creating a class that extends React.Component

        20. Render method is present because this is a class and you have control over your lifecycle.

            12. Your render holds your return as well as can hold other javascript that is needed at render (like variable declaration).

            13. Typically, functions that you call in your return will be put outside of render. These can be prior or after the render method in order, but it’s better to use the following flow inside your class components:

                7. open the class

                8. constructor

                    1. with state and super

                9. lifecycle methods: componentDidMount, etc.

                10. functions

                11. render

                12. close the class

        21. The return statement is what should be returned (HTML)

    25. Notice the the render method is a class method (a function of the class)

        22. The return statement within it, however, is not. 

        23. It functions more like a console.log statement but will return HTML so that you can use it.

        24. Notice the syntax is 
return( 
	<!--Some HTML--> 
);

**Assignment:  1 page wonder (from git-collaborative-project) but we will do as a react application. Each section is a component, and we will add those components to the main view.**