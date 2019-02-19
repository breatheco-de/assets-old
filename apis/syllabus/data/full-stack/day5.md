**Day 5 - Git and Github**

**Welcome everyone, Check if they were able to finish all the lessons up till now.**

1. **Creating SSH Keys**

    1. When you push changes to your github account, you have two addresses you can send to for a repository: SSH and HTTP

        1. HTTP will require you to enter your credentials every time you push changes

        2. SSH will use a secure key to authenticate you so that you don’t have to type username and password 

    2. **To create an SSH Key on github**

        3. Click your profile picture (top right)

        4. then select settings

        5. Finally select SSH and GPG Keys (This is where you will manage your keys)

        6. In a separate tab, visit this address:   [https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/#generating-a-new-ssh-key](https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/#generating-a-new-ssh-key) and follow the instructions for how to create a new key in your command line

        7. Go back to your SSH keys page on github and hit "New Key" button

        8. Type in a title for your key (can be anything to help you identify it) and then copy and paste the key you generated into the key area.

2. **Using Github**

    3. As we mentioned in previous classes, Github and Git have become a staple of every development workflow.

    4. You will use this in EVERY development job you have from here forward.

    5. Show the main profile screen and explain parts

        9. use [https://github.com/alesanchezr/](https://github.com/alesanchezr/) as example

    6. Explain how to create a repository

        10. click repository tab > new repo button > fill out data

    7. Show what a repository looks like

        11. explain the contents of the repository and the importance of the Readme file in a project

        12. show [https://github.com/4GeeksAcademy/react-flux-dash](https://github.com/4GeeksAcademy/react-flux-dash) as an example 

        13. explain badges and activity on a repo

3. **The Commit Object**

    8. [https://breatheco.de/en/lesson/learn-git-version-control-system/#commit-objects](https://breatheco.de/en/lesson/learn-git-version-control-system/#commit-objects) 

    9. Basically a snapshot of what your project looked at during the moment of your commit. 

    10. Commits represent a change in the current code, so if you don’t have any changes, you will receive a message saying there is nothing to commit.

    11. Three main things in a commit:

        14. Set of files which make up your snapshot at that time

        15. Instructions pointing to the Parent commit objects

        16. An SH1 Name that serves as a unique identifier for that specific commit.

    12. Commits also typically have comments, which should explain what was changed or addressed in the current commit

4. **The HEAD**

    13. The heads of the repository are like the "revision history of the project."  

    14. A revision history is a list of commit objects that altogether contain all the changes that you and your other team members have made to the project files.

    15. ***Every time you make a new commit, the head will move to that new commit.  (This way you are able to have access to the entire project history of commits.)***

    16. It is possible to move the head to a different commit, however, you must remember that commits made after the commit to which the head is pointing at are not possible to be reviewed.

5. **The Stage**

    17. When creating a commit, you select which files you wish to add to the commit. This is called "staging" your commit. (basically, preparing your formal commit object)

    18. Typically, you will use "git add ." which adds all files to your stage for the current commit, then follow it with your commit.

6. **Branch**

    19. As we mentioned briefly last class, a branch allows you to work on a specific feature of code without compromising the main code in a repository.  [https://breatheco.de/en/lesson/learn-git-version-control-system/#heads](https://breatheco.de/en/lesson/learn-git-version-control-system/#heads) 

    20. That means that each branch is a separate revision history.

    21. The main branch of code in the repository is called "Master" typically

    22. You can have as many different branches as you want in a repository/project.

    23. Each branch has it’s own head which resides on the last commit of that branch. So if you have two branches, "Master" and “login”, each will have their own head on the last commit.

    24. When you are satisfied with the feature you developed, you can merge it back in to master and it will bring all code changes, along with replace the head on master with the new head.

7. **Git FLOW (profesional branching)**

    25. All new projects have to be initialized with git.  (git init)

        17. This creates a git folder with all elements that define the git project.

        18. At this point, it’s also good to set the remote for your project. This will tell the project what the address is for your repository so that you can push changes later.

            1. the command for this is:  git remote add origin https://github.com/user/repo.git 

            2. where the address at the end is your repository address or SSH address.

    26. Whenever you have to stage your changes, you use "git add". As mentioned before, to add all file changes, “git add .” 

        19. conversely, you can condense this into the git commit by adding a flag "-a" which means add all files to the commit stage

    27. Once files are staged, you create a commit object by using "git commit -m ‘commit message’" or if you have not used git add, you can use the -a flag as mentioned before.

    28. At this point, you want to use ‘git push’ to upload your changes

    29. If you need to create a new branch:

        20. git branch [new head/branch name]

        21. next you need to switch to that branch to work on it

            3. git checkout [head/branch name]

        22. when you complete your changes on a new branch, you merge the changes back in using the following:

            4. git merge [head]
git pull . [head]

            5. These two commands will essentially check for and resolve conflicts and then merge the data back into the master while replacing the head with your new head.

8. **Commit vs PUSH**

    30. We already discussed that a commit will allow you to create a new commit object. This is a local bundle you are preparing to upload.

    31. When you finish your commit, you have to attempt to upload that code to the repository.

    32. This is where git push comes in. It allows you to attempt to upload your changes to the repository.

    33. If there are any issues where a conflict has arisen, you will have to resolve the conflicts before pushing your changes.

9. **Resolving Conflicts**

    34. For conflicts, git will notify you that there are issues.

        23. this typically occurs when two people are trying to modify the same file(s).

    35. To proceed you need to:

        24. Get the new changes downloaded to your computer with ‘git pull’ or if you are on a branch, ‘git pull origin branch-name’

        25. Then you will be asked to merge the changes 

        26. Here you will have to edit the files with conflicts and resolve any changes. 

        27. Once you have resolved the conflicts, you can attempt to push changes again.

10. **I recommend that you review the git lesson on your own time from breatheco.de and any topics you don’t understand, try to google the answers.**

    36. use reputable sources like stack overflow, the git documentation, etc.

    37. if you still have questions after your research, you can always chat me or another teacher

    38. you can also ask questions in slack by using @here

**At this point we will start the assignment for the git collaborative portfolio project.**

**Break the class into groups and assign a section of the portfolio project**

* **[https://github.com/breatheco-de/exercise-git-collabration/blob/master/website1/designs/guide.jpg]**(https://github.com/breatheco-de/exercise-git-collabration/blob/master/website1/designs/guide.jpg)

* Use peer programming to complete the section quickly, then upload the changes to the repository your professor has provided.

Teacher will clone the repo in a new class repository and will handle updating the name of the template parts so that the site functions properly.

**Homework: **Create repositories for all previous workspaces and upload all your code to their corresponding repo’s. Then submit the assignments on your student.breatheco.de  **(DUE MONDAY). Also, work on all repl’its and get caught up. Be ready for the next class which is on Wireframing and design process.**