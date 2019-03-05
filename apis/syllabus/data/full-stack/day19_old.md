**Day 19 -  React Context API**

**Welcome class.**

1. **History**

    1. **MVC**

        1. **What is MVC**

            1. A design pattern that separates your software into 3 logical blocks to organize everything between front and back end.

            2. Draw data flow diagram for typical MVC.

        2. **How does it work**

            3. **View-** The user interface/front end. This is where user will interact with the software through actions.

            4. **Controller-** Those actions are interpreted by the controller which serves as a logical layer. The controller then requests the data from the Model.

            5. **Model-** The model is where the data is warehoused. This interacts with the database to retrieve any information that is requested. After the requested data is accessed, it’s sent back up to the view, via the controller.

        3. **What problem it solves**

            6. This provides a structured approach at software development which organizes data flow through the application.

    2. **Flux**

        4. **MVC in React**

            7. Flux was invented by Facebook and allows you to perform MVC in react.

            8. Draw data flow diagram for Flux.

        5. **How does it work**

            9. **View-** This again, is the user interaction point. User interacts with your application and it triggers actions.

            10. **Actions-** actions communicate directly with the store or with API data, which then gets placed in the store.

            11. **Store-** Store then warehouses data in the component state state.

            12. **Dispatcher-** The dispatcher will notify all components that are listening to the changes in the store.

        6. **What does this solve**

            13. In react, you can only send props down to children via prop drilling. This means that data cannot go back up to parents and parents can only render the children as is.

            14. With flux, any component can listen for changes in the Store and access that data.

    3. **Context API**

        7. **Fast forward to Context.**

            15. Context API is best used with smaller applications and sparingly.

            16. It is a slimmed down version of flux that allows us to access data from any view.

            17. Show React Todo MVC example

        8. **How does it work**

            18. **Context is broken up into two main wrappers**

                1. **Context.Provider**

                    1. Provider is a wrapper that tells the context api who will be providing the data to other components.

                    2. In our unique rendition of Context, we abstract the Provider and use it to wrap our Store and Actions.

                    3. This gives all Consumers access to the Store and Actions.

                2. **Context.Consumer**

                    4. The consumer is a wrapper that is used around any view component that you want to access data or actions from the store.

                    5. With a consumer, we first import context into the component.

                    6. Then we wrap the part of our view that will be consuming the store.

                    7. Within that wrapper, we can access the data from the store. In our Todo example, we render a map of the store by using store.list.map and then pass our Store and Actions into the function as a deconstructed object set. This will give us access to all of their methods and properties.

                    8. We then proceed to perform our functions using dot notation to access actions or store properties.

        9. **When creating a context application, you can clone the repo or use the breatheco.de CLI to create your project.**

        10. **Make sure that you remove any sample files that are not needed for your project and proceed with re-tooling the boilerplate for your purposes.**

**Homework:** **Tweak your react todo so that you can use context to access your data.**