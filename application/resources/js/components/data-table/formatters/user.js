import defaults from './default';

const getUserName = (user) => {
  if (user.displayName) {
    return user.displayName;
  }

  if (user.lastName) {
    return `${user.lastName}, ${user.firstName}`;
  }

  return user.firstName;
};

const formatUser = (user) => {
  const content = `<small class="text-muted">#${user.id}</small>&ensp;${getUserName(user)}`;

  return user.links.self
    ? `<a href="${user.links.self}">${content}</a>`
    : content;
};

const replaceExtras = name => name.replace(/^(von|van|v\.)\s/, '');

export default {
  ...defaults,

  format: formatUser,
  groupBy: user => user.id,
  orderBy: user => `${user.lastName ? replaceExtras(user.lastName) : ''}${user.firstName}`.toLowerCase(),

  filterFunction: query => {
    const lowercase = query.toLowerCase();

    return (user) => (
      (user.displayName && user.displayName.toLowerCase().indexOf(lowercase) !== -1)
        || (user.lastName && user.lastName.toLowerCase().indexOf(lowercase) !== -1)
        || (user.firstName && user.firstName.toLowerCase().indexOf(lowercase) !== -1)
    );
  },

  defaultAggregator: 'count',

  aggregates: {
    count: {
      label: 'Anzahl',
      calculate: list => list.length,
      format: 'number',
    },
  },
};
