// Next.js Sidebar Component that consumes /api/v1/menus
// This is a conceptual example - actual implementation would depend on your Next.js setup

import { useState, useEffect } from 'react';

const Sidebar = () => {
  const [menuItems, setMenuItems] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchMenuItems();
  }, []);

  const fetchMenuItems = async () => {
    try {
      const response = await fetch('/api/v1/menus', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'Content-Type': 'application/json',
        },
      });
      
      if (response.ok) {
        const data = await response.json();
        setMenuItems(data.menus);
      }
    } catch (error) {
      console.error('Error fetching menu items:', error);
    } finally {
      setLoading(false);
    }
  };

  const renderMenuItem = (item) => {
    if (item.type === 'separator') {
      return (
        <div key={item.id} className="menu-separator">
          {item.label}
        </div>
      );
    }

    return (
      <div key={item.id} className="menu-item">
        {item.route ? (
          <a href={`/${item.route}`} className="menu-link">
            {item.icon && <span className="menu-icon">{item.icon}</span>}
            <span className="menu-label">{item.label}</span>
          </a>
        ) : item.url ? (
          <a href={item.url} className="menu-link" target="_blank" rel="noopener noreferrer">
            {item.icon && <span className="menu-icon">{item.icon}</span>}
            <span className="menu-label">{item.label}</span>
          </a>
        ) : (
          <div className="menu-label">{item.label}</div>
        )}
        
        {item.children && item.children.length > 0 && (
          <div className="submenu">
            {item.children.map(child => renderMenuItem(child))}
          </div>
        )}
      </div>
    );
  };

  if (loading) {
    return <div className="sidebar">Loading...</div>;
  }

  return (
    <aside className="sidebar">
      <div className="sidebar-header">
        <div className="sidebar-brand">
          <span className="brand-icon">⚡</span>
          <span className="brand-text">AppName</span>
        </div>
      </div>
      
      <nav className="sidebar-nav">
        {menuItems.map(item => renderMenuItem(item))}
      </nav>
    </aside>
  );
};

export default Sidebar;