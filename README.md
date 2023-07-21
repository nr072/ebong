# Ebong



## What is it?

This is a translation memory (TM) tool.
TM tools are software applications that contain translations of various phrases and sentences
that can be used later in translating similar phrases and sentences.
This greatly helps maintain consistency and
reduce the workload of translators by providing them with potential suggestions.



## Warning

This project is still under development. This is not a finished product.



## Features

- **Item categories**

  - Word

    May be a single word or a compound noun.
    Each spelling variation should be a separate Word.
    Every Word must belong to at most one Group.

  - Group

    An association of similar Words.
    This mainly includes various parts of speech of a word.

  - Sentence

    Not necessarily a complete grammatical sentence.
    May be an idiom or parts of a full sentence or a combnation of multiple sentences.
    Must contain at least one Group.
    On a technical level, Sentences are never associated to Words, only to Groups.

- **Creating new Words**

  - Fields: en, POS, associated Group (opt.)

- **Seeing a list of Words**

  - Has pagination

  - Can be searched by: en

- **Creating new Groups**

  - Fields: title, associated word(s)

- **Seeing a list of Words**

  - Has pagination

  - Can be searched by: en

- **Creating new Sentences**

  - Single Sentence creation and bulk creation
    - Bulk creation takes JSON input

  - Fields:
    - en and its bn
    - Associated Groups
      - Has autosuggestion
    - Context and subcontext
    - Source and 3 relevant links
    - Notes/references
    - Flags
      - Revision needed

- **Seeing a list of examples**

  - Has pagination

  - Can be searched by: en, bn, context/subcontext, Words/Groups, source



## Development setup

- `cd` to the project root.

- 



## Dependency

- Laravel
- Livewire
- Tailwind CSS



## Conventions

### Git commit messages

#### General instructions

- Commit message titles should be written in passive voice,
  with certains verbs omitted for brevity as in news titles,
  and in past tense.
  Example:

  ```
  sentence index pagination increased to 50
  ```

- Contrary to the grammatical rules of the English language,
  commit titles should neither start with capital letters
  (unless the first word is a proper noun)
  nor contain any ending punctuation.

- Titles should be single sentences.
  If more than one is needed,
  either move the details to the commit message body, or
  break the commit itself down to multiple commits
  each of which works towards a single objective.

- Do not use emoji characters in commit titles.

#### Commit title prefixes

The following inexhautive list contains prefixes that
are used at the beginning of commit message titles.
A prefix is followed by a colon and then by a space
before the actual commit title continues.

It is not mandatory for every title to have a prefix.

- `feat`

  Use this with commits that introduce a new, significant feature.
  Such a commit may be an empty one (without any code changes) that only refers to
  previous, regular commits (with their own, appropriate prefixes)
  used to implement various parts of said feature.

- `fix`

  Use this with commits that fix bugs.

- `maint`

  Indicates commits related to refactoring and other maintenance tasks.

- `ui`

  Indicates commits that primarily affect the user interface.
  Use this with all commits related to stylistic changes.

- `ux`

  Indicates commits that primarily affect the user experience.
  Use this with commits on quality-of-life improvements and
  small features that makes the application easier to use.

- `wip`

  Indicates commits that are work in progress.
  Use this if multiple commits contribute to the same feature.
  Such commits are usually incomplete on their own but
  would be too big if combined into a single commit.
  Also use this prefix if working on a commit has to be abandoned for some time
  and using `git stash` is not an option.

### Git branch naming

- Branch names should be clear but not overly descriptive.

- Words in branch names should be separated by hyphens.

- Development branch names should be prefixed with `dev--`.

- The `master` branch must not be directly worked on.
  All development branches should be first merged into `dev`,
  which in turn should be merged into `master`
  after tests and other necessary steps have been completed.

- A dedicated branch named `bugfixes` may be used for fixing bugs.

- Similarly named branches with singular, overall objectives may also be created (e.g., `test`).
  Or, if more than one branches are needed, prefixes such as `test--` may be used.
